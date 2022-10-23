<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([ 'email' => [__('The provided credentials are incorrect.')] ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('app')->plainTextToken
        ], 200);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => __("Logged out.")
        ]);
    }
}
