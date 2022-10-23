<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\MyStuff\Repos\AccountVerification\EmailVerification;
use App\Http\Traits\ProfilePictureTrait;

class RegisterController extends Controller
{
    use ProfilePictureTrait;

    public function register(RegisterRequest $request)
    {
        $pathsToStoredProfilePics = $this->storeTrait($request);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'image'     => $pathsToStoredProfilePics['images'],
            'thumbnail' => $pathsToStoredProfilePics['thumbnails'],
        ]);

        (new EmailVerification)->createOrUpdate($user);
        
        return response()->json([
            'success' => __('Your account has been created and verification email has been sent. Check your inbox.'),
            'user' => $user,
            'token' => $user->createToken('app')->plainTextToken
        ], 201);
    }
}
