<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateUserSettingsRequest;
use App\Http\Response\ApiResponse;
use App\MyStuff\Repos\UserSettings\UserSettingsEloquentRepo;
use App\Exceptions\InternalServerErrorException;
use App\MyStuff\Repos\User\UserEloquentRepo;

class ProfileController extends Controller
{
    public function updateProfile(UpdateUserSettingsRequest $request, UserEloquentRepo $userRepo, UserSettingsEloquentRepo $settingsRepo)
    {
        if(! $user = auth()->user())
            throw new InternalServerErrorException(__('serverError.failed'));

        if($request->userFields){
            if(! $userRepo->update($user, $request->userFields))
                throw new InternalServerErrorException(__('serverError.failed'));
        }

        if($request->settingsFiels){
            if(!$settingsRepo->update($user->userSetting, $request->settingsFiels))
                throw new InternalServerErrorException(__('serverError.failed'));
        }

        return response()->json( 
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => $userRepo->first(['email' => $user->email], ['userSetting'])
                ]
            ]) 
        );
    }

    public function user(){
        $user = auth()->user();
        $user->userSetting;

        return $user;
    }

    /**
     * Deletes user account, profile image and thumbnail
     */
    public function delete(UserEloquentRepo $userRepo)
    {
        $user = auth()->user();

        if(! $userRepo->delete($user))
            throw new InternalServerErrorException(__('serverError.failed'));

        $user->deleteProfileImages();

        return ApiResponse::success([
            'messages' => [[ __('profile.youDeletedAcc') ]],
        ]);
    }
}
