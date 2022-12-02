<?php

namespace App\MyStuff\Repos\ParticipantPivot;

use App\MyStuff\Repos\ParticipantPivot\Contracts\ParticipantPivotRepo;
use App\MyStuff\General\Traits\CRUDTrait;
use App\Models\Chat\ParticipantPivot;
use App\Models\Chat\ChatGroup;
use App\Models\Chat\ChatRole;
use App\Exceptions\InternalServerErrorException;

class ParticipantPivotEloquentRepo implements ParticipantPivotRepo
{
    use CRUDTrait;

    public function getModel()
    {
        return ParticipantPivot::class;
    }

    // order matters
    public function roleResolver($model_type)
    {
        if($model_type == ChatGroup::TYPE_PUBLIC_CLOSED) return ChatRole::LISTENER;
        
        return ChatRole::PARTICIPANT;
    }

    public function setupUsersPivots($chatGroup, $users_ids)
    {
        $pivots = [];
        $now = now();
        $selfId = auth()->user()->id;

        // add participants without self
        foreach($users_ids as $user_id){
            $pivots[] = [
                'user_id' => $user_id, 
                'group_id' => $chatGroup->id, 
                'last_message_seen_id' => null, 
                'participant_role' => $this->roleResolver($chatGroup->model_type),
                'accepted' => false, 
                'invited_by_user_id' => $selfId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // add self
        $pivots[] = [
            'user_id' => $selfId, 
            'group_id' => $chatGroup->id, 
            'last_message_seen_id' => null, 
            'participant_role' => ChatRole::CREATOR,
            'accepted' => true, 
            'invited_by_user_id' => $selfId,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        if(! $this->createMany($pivots))
            throw new InternalServerErrorException(__("serverError.failed")); 
    }

}