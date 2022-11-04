<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserSettings;
use App\Models\User;

class UserPopulusSeeder extends Seeder
{
    public function run()
    {
        User::
            factory(100)
            ->has(UserSettings::factory())
            ->create(['first_name' => 'Test']);
    }
}
