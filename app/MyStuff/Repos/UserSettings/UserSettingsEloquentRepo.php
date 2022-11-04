<?php

namespace App\MyStuff\Repos\UserSettings;

use App\MyStuff\Repos\UserSettings\Contracts\UserSettingsRepo;
use App\MyStuff\General\Traits\CRUDTrait;
use App\Models\UserSettings;
use Illuminate\Support\Facades\DB;

class UserSettingsEloquentRepo implements UserSettingsRepo
{
    use CRUDTrait;

    public function getModel()
    {
        return User::class;
    }

}