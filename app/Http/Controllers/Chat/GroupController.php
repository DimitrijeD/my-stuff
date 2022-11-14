<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;

use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotEloquentRepo;
use App\Http\Requests\ChatGroup\ChangeGroupNameRequest;
use App\Http\Requests\ChatGroup\StoreGroupRequest;
use Illuminate\Http\Request;
use App\Events\GroupNameChangeEvent;
use App\Events\GroupTypeChangeEvent;

use App\Exceptions\InternalServerErrorException;
use App\Http\Response\ApiResponse;
use App\Http\Requests\ChatGroup\ChangeGroupTypeRequest;

class GroupController extends Controller
{
    protected $chatGroupRepo;

    public function __construct(ChatGroupEloquentRepo $chatGroupRepo)
    {
        $this->chatGroupRepo = $chatGroupRepo;
    }

    public function store(StoreGroupRequest $request, ParticipantPivotEloquentRepo $participantPivotRepo)
    {
        $chatGroup = $this->chatGroupRepo->create([
            'name' => $request->name,
            'model_type' => $request->model_type,
        ]);

        $request_initiator_id = auth()->user()->id;

        // @todo fix this nonsence
        foreach($request->users_ids as $user_id){
            $participantPivotRepo->create([
                'user_id' => $user_id, 
                'group_id' => $chatGroup->id, 
                'last_message_seen_id' => null, 
                'participant_role' => $participantPivotRepo->roleResolver($user_id, $request_initiator_id, $chatGroup->model_type),
            ]);
        }

        return response()->json($this->chatGroupRepo->first(['id' => $chatGroup->id], ['participants']), 201);
    }

    public function getGroupsByUser()
    {
        $groups = (auth()->user()->groups()->with(['participants', 'lastMessage.user']))
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($groups, 200);
    }

    public function getGroupById(Request $request)
    {
        $group = $this->chatGroupRepo->get(
            ['id' => $request->group_id], 
            ['participants', 'lastMessage.user']
        );

        return $group
            ? response()->json($group)
            : response()->json(null, 404);
    }

    public function changeGroupName(ChangeGroupNameRequest $request)
    {
        if( !$this->chatGroupRepo->update($request->group, [ 'name' => $request->new_name ]) )
            throw new InternalServerErrorException(__("chat.name.failedUpdating")); 

        broadcast(new GroupNameChangeEvent([
            'group_id' => $request->group->id,
            'new_name' => $request->new_name
        ]));
        
        return response()->json( ApiResponse::success([
            'messages' => [[ __('chat.name.updated') ]],
        ]) );
    }

    // public function refreshGroup(Request $request)
    // {
    //     $group = $this->chatGroupRepo->get(
    //         ['id' => $request->group_id], 
    //         ['participants', 'latestMessages.user']
    //     );

    //     return $group->participants->where('id', auth()->user()->id)->first() // if user is participant
    //         ? response()->json($group)
    //         : response()->json(['errors' => __("You have no access rights to this chat group.")], 403);
    // }

    public function chageGroupType(ChangeGroupTypeRequest $request)
    {
        if( !$this->chatGroupRepo->update($request->group, [ 'model_type' => $request->model_type ]) )
            throw new InternalServerErrorException(__("chat.type.failedUpdating")); 

        broadcast(new GroupTypeChangeEvent([
            'model_type' => $request->model_type,
            'group_id' => $request->group->id
        ]));
        
        return response()->json( ApiResponse::success([
            'messages' => [[ __('chat.type.success') ]],
        ]) );
    }
}
