<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\MyStuff\Repos\AccountVerification\EmailVerification;
use App\Http\Response\ApiResponse;
use Illuminate\Validation\ValidationException;

class AccountVerificationController extends Controller
{
    public function createOrUpdateForEmail()
    {
        if(!$user = auth('sanctum')->user()) 
            throw ValidationException::withMessages([ __('email.email_verification.requires_login') ]);

        return response()->json( ApiResponse::success([
            'messages' => [ 
                (new EmailVerification)->createOrUpdate($user),
            ],
        ]) );
    }
}
