<?php

namespace App\MyStuff\Repos\AccountVerification;

use App\MyStuff\Repos\User\UserEloquentRepo;
use App\MyStuff\Repos\AccountVerification\AccountVerificationEloquentRepo;
use App\Models\Auth\AccountVerification;
use App\Jobs\EmailVerificationJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EmailVerification
{
    public function __construct()
    {
        $this->userRepo = new UserEloquentRepo; 
        $this->accountVerificationRepo = new AccountVerificationEloquentRepo; 
    }

    /**
     * Creates or updates user's email verification
     */
    public function createOrUpdate(User $user)
    {
        $accoutVerification = $user->account_verification;

        if(!$accoutVerification && $user->email_verified_at){
            return ['success' => __('auth.already_verified')];
        }

        if($accoutVerification && $user->email_verified_at){
            $accoutVerification->delete();
            return ['success' => __('auth.verified')];
        }

        $code = Str::random(AccountVerification::EMAIL_HASH_LENGTH);

        $num_of_attempts = $accoutVerification ? ($accoutVerification->num_of_attempts + 1) : 1;

        $verification = $this->accountVerificationRepo->updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => AccountVerification::EMAIL_TYPE,
            ],
            [
                'code' => Hash::make($code),
                'num_of_attempts' => $num_of_attempts
            ]
        );

        $emailData = [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'url' => $this->makeUrlFromCode($user->id, $code)
        ];
        
        dispatch(new EmailVerificationJob($emailData));

        return $num_of_attempts > 1 
            ? ['success' => __('email.email_verification.another_email_dispatched', ['email' => $user->email])]
            : ['success' => __('email.email_verification.email_dispatched')];
    }

    /**
     * Creates URL from parameters
     */
    public function makeUrlFromCode($user_id, $code)
    {
        return url("/email-verification/uid/{$user_id}/c/{$code}");
    }

    /**
     * User attempts to verify his account
     * 
     * Returns string (key) which determines what to disply to user
     */
    public function attempt(User $user, $accoutVerification, $code)
    {
        // $code matches hash, verify user
        if( Hash::check($code, $accoutVerification->code) ){
            $accoutVerification->delete();
            return $this->userRepo->update($user, ['email_verified_at' => now()]);
        }

        // All cases exhausted, inc verification's num_of_attempts and return 404
        $this->accountVerificationRepo->update($accoutVerification, ['num_of_attempts' => $accoutVerification->num_of_attempts + 1]);

        return false;
    }
}