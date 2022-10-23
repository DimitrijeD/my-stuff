<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Cache\ChatRoleRulesCache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Execute all of them by:
     *      php artisan db:seed
     * 
     * @return void
     */
    public function run()
    {
        (new ChatGroupClusterSeeder)->run();
        (new UserPopulusSeeder)     ->run();
        (new ChatRoleRulesCache)->storeAll();
    }
}
