<?php

namespace App\MyStuff\Repos\Auth\PasswordReset;

use App\MyStuff\Repos\Auth\PasswordReset\Contracts\PasswordResetRepo;
use App\MyStuff\General\Traits\CRUDTrait;
use App\Models\Auth\PasswordReset;
use App\Jobs\PasswordResetEmailJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\MyStuff\Repos\User\UserEloquentRepo;

class PasswordResetEloquentRepo implements PasswordResetRepo
{
    use CRUDTrait;

    public function __construct(UserEloquentRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getModel()
    {
        return PasswordReset::class;
    }

    public function createOrUpdateRequestEmail($email)
    {
        if(!$user = $this->userRepo->first(['email' => $email], ['password_resets'])) return PasswordReset::FAKE_SUCCESS_KEY;

        if($user->password_resets?->attempts > PasswordReset::MAX_REQUESTS) return PasswordReset::MAX_REQUESTS_EXCEEDED_KEY;

        $key = $user->password_resets ? PasswordReset::UPDATE_KEY : PasswordReset::SUCCESS_KEY;

        $token = Str::random(PasswordReset::EMAIL_HASH_LENGTH);

        $storeStatus = $this->updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'attempts' => $user->password_resets?->attempts 
                    ? $user->password_resets->attempts + 1 // update old 
                    : 1, // make new 
                'token' => Hash::make($token),
            ]
        );

        // if(!$storeStatus) throw exception

        dispatch(new PasswordResetEmailJob([ 
            'email' => $email,
            'url' => url("/reset-password?email={$email}&token={$token}"),
        ]));

        return $key;
    }

    public function resetPassword($email, $password, $token)
    {
        // Here I can check if user is loggedin and if his email and one provided in form match. Since I have no idea what to do in that case, I'll just leave a comment here.

        if(!$user = $this->userRepo->first(['email' => $email], ['password_resets'])) return 'VE006';

        if(!Hash::check($token, $user->password_resets?->token)) return 'VE007';

        $this->userRepo->update($user, [ 'password' => Hash::make($password) ]);
        $this->userRepo->delete($user->password_resets);

        return 'success';
    }


}