<?php

namespace App\Rules\Chat\Store;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneParticipantRule implements Rule
{
    protected $users_ids;
    const MIN_NUM_USERS_EXCEPT_SELF = 1;

    public function __construct($users_ids)
    {
        $this->users_ids = $users_ids; 
    }

    public function passes($attribute, $value)
    {
        return count($this->users_ids) >= self::MIN_NUM_USERS_EXCEPT_SELF;
    }

    public function message()
    {
        return __('chat.storeGroup.minNumUsers');
    }
}
