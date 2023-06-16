<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chat\ChatMessage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parent_model' => ChatMessage::class, 
            'parent_id' => ChatMessage::factory(), 
            'url' => 'some url to file', 
            'config_path' => 'some string used to store file in app',
        ];
    }
}
