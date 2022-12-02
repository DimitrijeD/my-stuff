<?php

namespace Database\Factories\Chat;

use App\Models\User;
use App\Models\Chat\ChatRole;
use App\Models\Chat\ChatGroup;
use App\Models\Chat\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantPivotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'group_id' => ChatGroup::factory(), 
            'last_message_seen_id' => ChatMessage::factory(), 
            'participant_role' => ChatRole::PARTICIPANT,
            'invited_by_user_id' => User::factory(), // @todo get/create some1 who can add users to chat and set him as inviter
            'accepted' => true,
        ];
    }
}
