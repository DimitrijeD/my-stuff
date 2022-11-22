<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Events\MeSawMessage;
use App\Events\MessageNotification;
use App\Events\NewChatMessage;
use App\Events\DeletedMessageEvent;
use App\Events\UpdateChatMessage;

use App\MyStuff\Repos\User\UserEloquentRepo;
use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\MyStuff\Repos\ChatMessage\ChatMessageEloquentRepo;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotEloquentRepo;

use App\Http\Requests\ChatMessage\StoreChatMessageRequest;
use App\Http\Requests\ChatMessage\SeenChatMessageRequest;
use App\Http\Requests\ChatMessage\DeleteMessageRequest;
use App\Http\Requests\ChatMessage\UpdateMessageRequest;

use App\Http\Response\ApiResponse;

use App\Exceptions\InternalServerErrorException;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $chatGroupRepo, $userRepo, $chatMessageRepo, $participantPivot;

    public function __construct(ChatGroupEloquentRepo $chatGroupRepo, UserEloquentRepo $userRepo, ChatMessageEloquentRepo $chatMessageRepo, ParticipantPivotEloquentRepo $participantPivot)
    {
        $this->chatGroupRepo = $chatGroupRepo;
        $this->userRepo = $userRepo;
        $this->chatMessageRepo = $chatMessageRepo;
        $this->participantPivot = $participantPivot;
    }

    public function getMissingMessages($group_id, $latest_msg_id)
    {
        return $this->chatMessageRepo->getMissingMessages($group_id, $latest_msg_id);
    }

    public function store(StoreChatMessageRequest $request)
    {
        $sender = auth()->user();

        $message = $this->chatMessageRepo->create([
            'user_id' => $sender->id,
            'group_id' => $request->group_id,
            'text' => $request->text,
            'edited' => false // @todo why doesnt ->default() in migration files not appply def value to collumn. Also why doesnt select query return that collumn if i do not define column on create
        ]);

        // @todo can be just update query
        $pivot = $this->participantPivot->first([
            'user_id' => $sender->id,
            'group_id' => $request->group_id
        ]);

        $this->participantPivot->update($pivot, [
            'last_message_seen_id' => $message->id,
            'updated_at' => now()
        ]);
        // ---

        // update groups field 'updated_at' to NOW in order to be able to sort users groups by 'time of last message'
        $this->chatGroupRepo->update(
            $this->chatGroupRepo->find($request->group_id), 
            [
                'updated_at' => now(), 
                'last_msg_id' => $message->id
            ]
        );

        broadcast(new NewChatMessage($message));

        $this->newChatMessageNotification($request->group_id, $sender, $message);

        return response()->json($message, 201);
    }

    /**
     * THIS REQUIRES COMPLETE REFACTOR
     * -- when user unlocks chat, he should auto listen for new messages in all chats.
     */
    private function newChatMessageNotification($group_id, $sender, $newMessage)
    {
        $group = $this->chatGroupRepo->find($group_id);
        $participants = $group->participants->where('id', '!=' , $sender->id);

        foreach ($participants as $participant){
            $messageNotification = [
                'group_id' => $group_id,
                'sender' => $sender,
                'created_at' => $newMessage->created_at,
                'forUserId' => $participant->id ,
            ];
            broadcast(new MessageNotification($messageNotification))->toOthers(); // @todo why is this in loop?
        }
    }

    public function messageIsSeen(SeenChatMessageRequest $request)
    {
        $user_id = auth()->user()->id;

        $pivot = $this->participantPivot->first([
            'user_id' => $user_id,
            'group_id' => $request->group_id
        ]);

        $updatedPivot = $this->participantPivot->update($pivot, [
            'last_message_seen_id' => $request->lastMessageId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($updatedPivot){
            broadcast(new MeSawMessage($updatedPivot));
            return response()->json('success', 200);
        }
        
        return response()->json('error', 400);
    }

    public function getLatestMessages(Request $request)
    {
        return $this->chatMessageRepo->getLatestMessages($request->group_id);
    }

    public function getBeforeMessage($group_id, $earliest_msg_id)
    {
        return $this->chatMessageRepo->getBeforeMessage($group_id, $earliest_msg_id);
    }

    public function delete(DeleteMessageRequest $request)
    {
        if(! $this->chatMessageRepo->delete($this->chatMessageRepo->find($request->message_id)))
            throw new InternalServerErrorException(__("model.genericGone")); 

        $eventPayload = [
            'message_id' => $request->message_id,
            'group_id' => $request->group_id
        ];

        if($request?->isLastMessage){
            $eventPayload['latest_message_after_delete'] = $request->group->lastMessage; 
        }

        broadcast(new DeletedMessageEvent($eventPayload));

        return response()->json( ApiResponse::success([
            [[ __('chat.message.deleted') ]],
        ]) );

    } 

    public function update(UpdateMessageRequest $request)
    {
        if(! $updatedMessage = $this->chatMessageRepo->update(
            $this->chatMessageRepo->find($request->message_id), 
            [
                'text' => $request->text,
                'edited' => true
            ]
        )) throw new InternalServerErrorException(__("serverError.failed")); 

        broadcast(new UpdateChatMessage($updatedMessage));

        return response()->json( ApiResponse::success([
            [[ __('chat.message.updated') ]],
        ]) );
    } 
}
