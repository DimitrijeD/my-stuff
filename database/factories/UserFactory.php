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
    const PASSWORD = 'qweqweqwe';
    const PASSWORD_HASH = '$2y$10$9X9xGbzD/tR4oMPEuhCNqe4Mkc08YyE8PkG5BOWrUX0tcaVrFxRNa'; 

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
            'email' => Str::random(2) . fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::PASSWORD_HASH,
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

    public static function getDefUser(){
        return [
            'first_name' => "Qwerty",
            'last_name' => "Qweerrtttyyyyyyyy",
            'email' => "qwe@qwe",
            'password' => self::PASSWORD,
        ];
    }
}
