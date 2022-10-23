<?php

namespace App\MyStuff\Repos\AccountVerification;

use App\MyStuff\Repos\AccountVerification\Contracts\AccountVerificationRepo;
use App\MyStuff\General\Traits\CRUDTrait;
use App\Models\AccountVerification;

class AccountVerificationEloquentRepo implements AccountVerificationRepo
{
    use CRUDTrait;

    public function getModel()
    {
        return AccountVerification::class;
    }

}