<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\MyStuff\Repos\AccountVerification\EmailVerification;

class AccountVerificationController extends Controller
{
    // @todo get mail status (sent or not) and return correct message
    public function createOrUpdateForEmail()
    {
        return response()->json((new EmailVerification)->createOrUpdate(auth('sanctum')->user()));
    }
}
