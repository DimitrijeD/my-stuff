<?php

namespace App\Rules\Chat\Store;

use Illuminate\Contracts\Validation\Rule;
use App\MyStuff\Repos\User\UserEloquentRepo;

class ParticipantsExistRule implements Rule
{
    protected $arrayOfUserIds;

    public function __construct($users_ids)
    {
        $this->users_ids = $users_ids; 
        $this->userRepo = new UserEloquentRepo;
    }

    public function passes($attribute, $value)
    {
        if(!$users = $this->userRepo->getMany(['id' => $this->users_ids])) return false;

        return count($users) == count($this->users_ids) ? true : false;
    }

    public function message()
    {
        return __("chat.storeGroup.someUsersDontExist");
    }
}
