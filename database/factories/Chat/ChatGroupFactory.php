<?php

namespace Database\Factories\Chat;

use App\Models\Chat\ChatGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Default group name',
            'model_type' => ChatGroup::TYPE_DEFAULT,
        ];
    }
}
