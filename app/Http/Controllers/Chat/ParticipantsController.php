<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotFormatter;
use App\MyStuff\Repos\ChatGroup\ChatGroupEloquentRepo;
use App\MyStuff\Repos\ParticipantPivot\ParticipantPivotEloquentRepo;

use App\Http\Requests\Chat\Participant\AddParticipantRequest;
use App\Http\Requests\Chat\Participant\ChangeRoleRequest;
use App\Http\Requests\Chat\Participant\RemoveParticipantRequest;
use App\Http\Requests\Chat\Group\UsersResponseToInvitationRequest;

use App\Events\ChatEvents\ParticipantEvents\UserRoleInGroupChangedEvent;
use App\Events\ChatEvents\ParticipantEvents\UserRemovedFromGroupEvent;
use App\Events\ChatEvents\ParticipantEvents\UserAddedToGroupEvent;
use App\Events\ChatEvents\ParticipantEvents\UserLeftGroupEvent;
use App\Events\ChatEvents\GroupEvents\NewChatGroupEvent;
use App\Events\ChatEvents\ParticipantEvents\ParticipantInvitationResponseEvent;

use App\Http\Response\ApiResponse;
use Illuminate\Validation\ValidationException;
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
            
        $freshGroup = $chatGroupRepo->get(
            ['id' => $request->group_id], 
            ['participants']
        );

        broadcast(new UserAddedToGroupEvent([
            'group_id' => $freshGroup->id,
            'participants' => $freshGroup->participants,
            'addedUsers' => $request->usersToAdd
        ]));
                    
        $addedUsers = [];

        foreach($request->usersToAdd as $user){
            $addedUsers[] = $user['user_id'];
        }

        broadcast(new NewChatGroupEvent($freshGroup, $addedUsers));

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

    /**
     * If user accepted invitation to chat, update his pivot and dispatch event
     * if user declined invitation, delete his pivot and dispatch event
     */
    public function usersResponseToInvitation(UsersResponseToInvitationRequest $request, ParticipantPivotEloquentRepo $participantPivotRepo)
    {
        $myPivot = $request->group->participants->where('id', auth()->user()->id)->first()->pivot;

        if($request->responseToInvitation){
            if(! $participantPivotRepo->update($myPivot, ['accepted' => true]) ) throw new InternalServerErrorException(__("serverError.failed"));
        } else {
            if(! $participantPivotRepo->delete($myPivot)) throw new InternalServerErrorException(__("serverError.failed"));
        }

        broadcast(new ParticipantInvitationResponseEvent([
            'accepted' => $request->responseToInvitation,
            'group_id' => $request->group->id,
            'user_id'  => auth()->user()->id
        ]));

        return response()->json(null, 201);
    }
}
