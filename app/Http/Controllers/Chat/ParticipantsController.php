<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotFormatter;
use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotEloquentRepo;

use App\Http\Requests\Participant\AddParticipantRequest;
use App\Http\Requests\Participant\ChangeRoleRequest;
use App\Http\Requests\Participant\RemoveParticipantRequest;

use App\Events\UserRoleInGroupChangedEvent;
use App\Events\UserRemovedFromGroupEvent;
use App\Events\UserAddedToGroupEvent;
use App\Events\UserLeftGroupEvent;

use App\Http\Response\ApiResponse;
use Illuminate\Validation\ValidationException;
use App\Exceptions\ModelDoesntExistException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ModelGoneException;

class ParticipantsController extends Controller
{ 
    protected $pivotRepo;

    public function __construct(ParticipantPivotEloquentRepo $pivotRepo)
    {
        $this->pivotRepo = $pivotRepo;
    }

    public function addUsersToGroup( AddParticipantRequest $request, ParticipantPivotFormatter $pivotFormatter, ChatGroupEloquentRepo $chatGroupRepo )
    {
        if($this->pivotRepo->getManyUnique($pivotFormatter->prepareManyToSelect(
            $request->usersToAdd, 
            $request->group_id
        ))) throw ValidationException::withMessages([[ __('chat.participants.add.alreadyPresent') ]]); // @todo no need to throw exception, just trim those which are in group, and add rest

        if(!$this->pivotRepo->createMany($pivotFormatter->prepareManyToInsert($request->usersToAdd, $request->group_id)))
            throw new InternalServerErrorException(__('chat.participants.add.failOnCreate'));
            
        broadcast(new UserAddedToGroupEvent([
            'group_id' => $request->group->id,
            'participants' => $chatGroupRepo->get(
                ['id' => $request->group_id], 
                ['participants']
            )->participants,
            'addedUsers' => $request->usersToAdd
        ]));
        
        return response()->json( ApiResponse::success([
            'messages' => [[ trans_choice('chat.participants.add.success', count($request->usersToAdd)) ]],
        ]) );
    }

    public function leaveGroup(Request $request)
    {
        if(!$pivot = $request
            ->group
            ->participants
            ->where('id', $request->user->id)
            ->first()
            ->pivot
        ) throw new InternalServerErrorException(); 

        if(!$this->pivotRepo->delete($pivot))
            throw new InternalServerErrorException(); 

        broadcast(new UserLeftGroupEvent([
            'group_id' => $request->group->id,
            'user_left_id' => $request->user->id
        ]))->toOthers();

        return response()->json( ApiResponse::info([
            'messages' => [[ __('chat.participants.leave.success') ]],
        ]) );
    }

    public function removeUserFromGroup(RemoveParticipantRequest $request)
    {
        if(!$this->pivotRepo->delete(
            $request
            ->group
            ->participants
            ->where('id', $request->remove_user_id)
            ->first()
            ?->pivot
        )) throw new InternalServerErrorException(__("serverError.chat.delete.participant")); 

        broadcast(new UserRemovedFromGroupEvent(array_merge(
            [
                'group_id' => $request->group->id,
                'removed_user_id' => $request->remove_user_id,
            ], 
            ApiResponse::info([
                'messages' => [[ __('chat.participants.remove.youRemoved', ['name' => $request->group->name]) ]],
            ])
        )));

        return response()->json( ApiResponse::info([
            'messages' => [[ __('chat.participants.remove.success') ]],
        ]) );
    }

    public function chageParticipantsRole(ChangeRoleRequest $request)
    {
        if(!$targetPivot = $request
            ->group
            ->participants
            ->where('id', $request->target_user_id)
            ->first()
            ?->pivot
        ) throw new ModelGoneException(__("chat.participants.role.gone")); 
            
        if(!$updatedPivot = $this->pivotRepo->update($targetPivot, [
            'participant_role' => $request->to_role,
        ])) throw new InternalServerErrorException(__("serverError.chat.role.change")); 

        broadcast(new UserRoleInGroupChangedEvent(array_merge(
            [
                'group_id' => $request->group->id,
                'pivot' => $updatedPivot,
            ], 
            ApiResponse::info([
                'messages' => [[ __('chat.participants.role.forTargetChange') ]],
            ])
        )));

        return response()->json( ApiResponse::success([
            'messages' => [[ __('chat.participants.role.change') ]],
        ]) );

    }

}
