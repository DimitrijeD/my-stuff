<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MyStuff\Repos\User\UserEloquentRepo;
use App\MyStuff\Repos\AccountVerification\AccountVerificationEloquentRepo;
use App\MyStuff\Repos\AccountVerification\EmailVerification;

use App\Models\AccountVerification;

class AuthenticationController extends Controller
{
    public function emailVerificationAttempt(Request $request, UserEloquentRepo $userRepo)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => ['required', 'integer'],
            'code' =>  ['required', 'string', 'size:' . AccountVerification::EMAIL_HASH_LENGTH, ],
        ]);

        if(!$user = $userRepo->find($request->user_id))
            abort(403);

        $status = (new EmailVerification)->attempt($user, $request->code);

        switch($status){
            case 'success':
                return response()->json([
                    'status' => 'success',
                    'message' => __("Account validated."),
                    'user' => $user,
                ]);

            case 'already_verified':
                return response()->json([
                    'status' => 'success',
                    'message' => __("You are already verified."),
                    'user' => $user,
                ]);

            case 'not_verified_no_verification':
                return response()->json([
                    'status' => 'error',
                    'message' => __("There was a problem with your verification. You can resend verification."),
                    'user' => $user,
                ]);

            case '404':
                return response()->json([
                    'status' => 'error',
                    'code' => 404
                ]);

            default:
                return response()->json([
                    'status' => 'error',
                    'code' => 404
                ]);
        }
    }

    public function isUserValidated(Request $request)
    {
        $user = auth()->user();

        if($user->email_verified_at ){
            return response()->json([
                'status' => 'already_verified',
                'message' => __("You are already verified."),
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => 'not_verified',
            'message' => __("Please verify your account."),
            'user' => $user,
        ]);
    }

}
