<?php

namespace App\MyStuff\Repos\ParticipantPivot;

class ParticipantPivotFormatter 
{
    public function prepareManyToInsert(array $users, $group_id)
    {
        $data = [];
        
        foreach($users as $user){
            $data[] = [
                'user_id' => $user['user_id'],
                'participant_role' => $user['target_role'],
                'group_id' => $group_id,
                'accepted' => false,
                'invited_by_user_id' => auth()->user()->id,
            ];
        }

        return $data;
    }

    public function prepareManyToSelect(array $users, $group_id)
    {
        $data = [];
        
        foreach($users as $user){
            $data[] = [
                'user_id' => $user['user_id'],
                'group_id' => $group_id
            ];
        }

        return $data;
    }

}