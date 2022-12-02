<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Events\ChatEvents\MessageEvents\MeSawMessage;
use App\Events\ChatEvents\MessageEvents\NewChatMessage;
use App\Events\ChatEvents\MessageEvents\DeletedMessageEvent;
use App\Events\ChatEvents\MessageEvents\UpdateChatMessage;

use App\MyStuff\Repos\User\UserEloquentRepo;
use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\MyStuff\Repos\ChatMessage\ChatMessageEloquentRepo;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotEloquentRepo;

use App\Http\Requests\Chat\Message\StoreChatMessageRequest;
use App\Http\Requests\Chat\Message\SeenChatMessageRequest;
use App\Http\Requests\Chat\Message\DeleteMessageRequest;
use App\Http\Requests\Chat\Message\UpdateMessageRequest;
use App\Http\Requests\Chat\Message\GetEarlyerMessagesRequest;
use App\Http\Requests\Chat\Message\GetMissingMessagesRequest;
use App\Http\Requests\Chat\Message\GetLatestMessagesRequest;

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


    public function store(StoreChatMessageRequest $request)
    {
        $sender = auth()->user();

        $message = $this->chatMessageRepo->create([
            'user_id' => $sender->id,
            'group_id' => $request->group_id,
            'text' => $request->text,
            'edited' => false // @todo why doesnt ->default() in migration files not appply def value to collumn. Also why doesnt select query return that collumn if i do not define column on create
        ]);

        $this->chatGroupRepo->update(
            $request->group, [
                'updated_at' => now(), 
                'last_msg_id' => $message->id
            ]
        );

        broadcast(new NewChatMessage($message));

        return response()->json([], 201);
    }

    public function messageIsSeen(SeenChatMessageRequest $request)
    {
        if(!$updatedPivot = $this->participantPivot->update(
            $this->participantPivot->first([
                'user_id' => auth()->user()->id,
                'group_id' => $request->group_id
            ]), [
                'last_message_seen_id' => $request->message_id_saw,
                'updated_at' => date('Y-m-d H:i:s')
            ]
        )) throw new InternalServerErrorException(__("serverError.failed")); 

        broadcast(new MeSawMessage($updatedPivot));

        return response()->json([], 200);
    }

    public function getLatestMessages(GetLatestMessagesRequest $request)
    {
        return response()->json($this->chatMessageRepo->getLatestMessages($request->group_id), 200);
    }

    public function getBeforeMessage(GetEarlyerMessagesRequest $request)
    {
        return response()->json($this->chatMessageRepo->getBeforeMessage($request->group_id, $request->earliest_msg_id), 200);
    }

    public function getMissingMessages(GetMissingMessagesRequest $request)
    {
        return response()->json($this->chatMessageRepo->getMissingMessages($request->group_id, $request->latest_msg_id), 200);
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
