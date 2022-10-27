<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\MyStuff\Repos\Auth\PasswordReset\PasswordResetEloquentRepo;
use App\Models\Auth\PasswordReset;
use App\Http\Response\ApiResponse;

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

    public function forgotPasswordGiveMeEmail(ForgotPasswordRequest $request, PasswordResetEloquentRepo $passwordResetrepo)
    {
        switch($passwordResetrepo->createOrUpdateRequestEmail($request->email)){
            case PasswordReset::SUCCESS_KEY:
                return response()->json(
                    ApiResponse::success([
                        'messages' => ['success' => [__('auth.forgot_password_email.success', ['email' => $request->email])]]
                    ])
                );

            case PasswordReset::UPDATE_KEY:
                return response()->json(
                    ApiResponse::success([
                        'messages' => ['success' =>[__('auth.forgot_password_email.update', ['email' => $request->email])]]
                    ])
                );

            case PasswordReset::MAX_REQUESTS_EXCEEDED_KEY:
                throw ValidationException::withMessages(['error' =>__('auth.forgot_password_email.max_requests_exceeded', ['email' => $request->email])]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request, PasswordResetEloquentRepo $passwordResetrepo)
    {
        switch($passwordResetrepo->resetPassword(
            $request->email, 
            $request->password, 
            $request->token
        )){
            case 'success':
                return response()->json(
                    ApiResponse::success([
                        'messages' => auth('sanctum')->user() 
                            ? __('passwords.success_reset_authenticated') 
                            : __('passwords.success_reset_unathenticated'), 
                    ])
                , 200);

            case 'VE006':
                throw ValidationException::withMessages([ 'error' => __('passwords.VE006')]);

            case 'VE007':
                throw ValidationException::withMessages([ 'error' => __('passwords.VE007')]);
        }
    }
}
