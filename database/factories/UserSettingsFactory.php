<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\UserSettings;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSettings>
 */
class UserSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'open_all_chats_on_new_message' => UserSettings::OPEN_ALL_CHATS_ON_NEW_MESSAGE,
            'show_only_important_notifications' => UserSettings::SHOW_ONLY_IMPORTANT_NOTIFICATIONS,
            'theme' => UserSettings::DEFAULT_THEME,
            'colorz' => null
        ];
    }
}
