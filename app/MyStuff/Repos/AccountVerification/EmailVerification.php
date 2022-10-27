<?php

namespace App\MyStuff\Repos\AccountVerification;

use App\MyStuff\Repos\User\UserEloquentRepo;
use App\MyStuff\Repos\AccountVerification\AccountVerificationEloquentRepo;
use App\Models\AccountVerification;
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
        if(!$user) return ['error' => "You must be logged in in order to verify account."];

        $accoutVerification = $user->account_verification;

        if(!$accoutVerification && $user->email_verified_at){
            return ['success' => __('You are verified')];
        }

        if($accoutVerification && $user->email_verified_at){
            $accoutVerification->delete();
            return ['success' => __('You are verified')];
        }

        $code = Str::random(AccountVerification::EMAIL_HASH_LENGTH);

        $verification = $this->accountVerificationRepo->updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => AccountVerification::EMAIL_TYPE,
            ],
            [
                'code' => Hash::make($code),
                'num_of_attempts' => $accoutVerification ? ($accoutVerification->num_of_attempts + 1) : 1
            ]
        );

        $emailData = [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'url' => $this->makeUrlFromCode($user->id, $code)
        ];
        
        dispatch(new EmailVerificationJob($emailData));

        return ['success' => __('Email has been sent. Check your inbox.')];
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
    public function attempt(User $user, $code)
    {
        $accoutVerification = $user->account_verification;

        // If email verification exists for what ever reason while user is already verified, something doesn't work
        if($accoutVerification && $user->email_verified_at){
            $accoutVerification->delete();
            return 'already_verified';
        }
        
        // If account verification doesnt exist and user is not verified, something doesn't work
        if(!$accoutVerification && !$user->email_verified_at)
            return 'not_verified_no_verification';

        // If account verification doesnt exist and user is verified
        if(!$accoutVerification && $user->email_verified_at)
            return 'already_verified';

        // $code matches hash, verify user
        if( Hash::check($code, $accoutVerification->code) ){
            $accoutVerification->delete();
            $this->userRepo->update($user, ['email_verified_at' => now()]);

            return 'success';
        }

        // All cases exhausted, inc verification's num_of_attempts and return 404
        $verification = $this->accountVerificationRepo->update($accoutVerification,['num_of_attempts' => $accoutVerification->num_of_attempts + 1]);

        return '404';
    }
}