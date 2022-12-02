<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MyStuff\Repos\User\UserEloquentRepo;
use App\MyStuff\Repos\AccountVerification\AccountVerificationEloquentRepo;
use App\MyStuff\Repos\AccountVerification\EmailVerification;
use App\Http\Response\ApiResponse;
use Illuminate\Validation\ValidationException;
use App\Models\Auth\AccountVerification;

class AuthenticationController extends Controller
{
    public function emailVerificationAttempt(Request $request, UserEloquentRepo $userRepo)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => ['required', 'integer'],
            'code' =>  ['required', 'string', 'size:' . AccountVerification::EMAIL_HASH_LENGTH, ],
        ]);

        if(!$user = $userRepo->find($request->user_id))
            throw ValidationException::withMessages([ __('email.email_verification.no_user_danger') ]);

        $accoutVerification = $user->account_verification;
        
        // If email verification exists for what ever reason while user is verified, something doesn't work
        if($accoutVerification && $user->email_verified_at){
            $accoutVerification->delete();
            return [
                'messages' => [ [ __("auth.already_verified") ] ],
                'data' => [
                    'status' => 'success',
                    'user' => $user,
                ]
            ];
        }

        // If account verification doesnt exist and user is not verified, something doesn't work
        if(!$accoutVerification && !$user->email_verified_at)
            throw ValidationException::withMessages([ __('email.email_verification.no_user_danger') ]);

        $user = (new EmailVerification)->attempt($user, $accoutVerification, $request->code);

        if($user)
            return ApiResponse::success([
                'messages' => [ [ __("email.email_verification.successfully_validated") ] ],
                'data' => [
                    'status' => 'success',
                    'user' => $user,
                ]
            ]);

        throw ValidationException::withMessages([ __("email.email_verification.incorrect_hash") ] );
    }

    public function isUserValidated(Request $request)
    {
        $user = auth('sanctum')->user();

        if($user->email_verified_at )
            return ApiResponse::success([
                'messages' => [[__('auth.already_verified')]],
                'data' => [
                    'status' => 'already_verified',
                    'user' => $user,
                ]
            ]);

        return ApiResponse::success([
            'messages' => [[__("email.email_verification.pending_verification")]],
            'data' => [
                'status' => 'not_verified',
                'user' => $user,
            ]
        ]);
    }

}
