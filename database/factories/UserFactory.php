<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    const PASSWORD = '$2y$10$OzosT7AoTUDVzRfml.zozOlsxSljp4q3zVOhC5TuZvmrNl.MknU9G'; // qweqweqweQ1

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $pic = $this->getRandomPicRelPath();

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::PASSWORD,
            'remember_token' => Str::random(10),
            'image' => $pic,
            'thumbnail' => $pic,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function getRandomPicRelPath()
    {
        $files = File::allFiles('public/basic-images');
        $randomFile = $files[rand(0, count($files) - 1)];

        return "/basic-images/" . $randomFile->getRelativePathName();
    }
}
