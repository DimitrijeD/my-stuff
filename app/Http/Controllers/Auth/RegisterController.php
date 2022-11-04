<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\MyStuff\Repos\AccountVerification\EmailVerification;
use App\Http\Traits\ProfilePictureTrait;
use App\Http\Response\ApiResponse;
use App\MyStuff\Repos\User\UserEloquentRepo;

class RegisterController extends Controller
{
    use ProfilePictureTrait;

    public function register(RegisterRequest $request, UserEloquentRepo $userRepo)
    {
        $pathsToStoredProfilePics = $this->storeTrait($request);

        $user = $userRepo->create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'image'     => $pathsToStoredProfilePics['images'],
            'thumbnail' => $pathsToStoredProfilePics['thumbnails'],
        ]);

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
