<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\MyStuff\Repos\AccountVerification\EmailVerification;
use App\Http\Response\ApiResponse;
use App\MyStuff\Repos\User\UserEloquentRepo;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request, UserEloquentRepo $userRepo)
    {
        $user = new User();

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->password   = Hash::make($request->password);
        
        $user->makeProfileImages($request?->file('profilePicture'));
        $user->save();
        $user->userSetting()->create();
        $user->userSetting;
        (new EmailVerification)->createOrUpdate($user);
        
        return response()->json(
            ApiResponse::success([
                'messages' => [ [__('auth.registered') ]],
                'data' => [
                    'user' => $user,
                    'token' => $user->createToken('app')->plainTextToken
                ]
            ]), 201
        );
    }
}
