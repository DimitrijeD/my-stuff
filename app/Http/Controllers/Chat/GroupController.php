<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;

use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotEloquentRepo;

use App\Http\Requests\Chat\Group\ChangeGroupNameRequest;
use App\Http\Requests\Chat\Group\StoreGroupRequest;
use App\Http\Requests\Chat\Group\ChangeGroupTypeRequest;

use App\Events\ChatEvents\GroupEvents\GroupNameChangeEvent;
use App\Events\ChatEvents\GroupEvents\GroupTypeChangeEvent;
use App\Events\ChatEvents\GroupEvents\NewChatGroupEvent;

use App\Exceptions\InternalServerErrorException;
use App\Http\Response\ApiResponse;
use Illuminate\Http\Request;

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
            'last_msg_id' => null
        ]);

        $participantPivotRepo->setupUsersPivots($chatGroup, $request->users_ids);

        $chatGroup->participants;
        
        broadcast(new NewChatGroupEvent($chatGroup, $request->users_ids));

        return response()->json(
            $chatGroup, 
            201
        );
    }

    /**
     * Returns all groups user belongs in sorted by latest updated
     */
    public function getGroupsByUser()
    {
        return response()->json(auth()->user()->groups, 200);
    }

    public function getGroupById(Request $request)
    {
        $request->group->lastMessage;
        $request->group->latestMessages;

        return response()->json($request->group);
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
