<?php

namespace Database\Factories\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Auth\PasswordReset;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\Model>
 */
class PasswordResetFactory extends Factory
{
    const TOKEN = 'token';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => User::factory()->create()->email,
            'token' => Hash::make(self::TOKEN),
            'attempts' => 1, 
        ];
    }
}
