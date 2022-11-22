<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AccountVerificationController;
use App\Http\Controllers\Auth\AuthenticationController;

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\GroupController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Chat\ParticipantsController;
use App\Http\Controllers\RoleRuleCachingController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;


Route::group(['middleware' => ['guest', 'throttle:10,1']], function () {  
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('forgot-password', [LoginController::class, 'forgotPasswordGiveMeEmail']);
});

Route::group(['middleware' => ['throttle:10,1']], function () {  
    Route::post('reset-password', [LoginController::class, 'resetPassword']);
});

Route::group(['prefix' => 'email-verification', 'middleware' => ['throttle:10,1', 'auth:sanctum']], function () {
    Route::post('create-or-update', [AccountVerificationController::class, 'createOrUpdateForEmail']);
    Route::get('uid/{user_id}/c/{code}', [AuthenticationController::class, 'emailVerificationAttempt']);
    Route::get('/is-validated', [AuthenticationController::class, 'isUserValidated']);
});


Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('user', [ProfileController::class, 'user']);
    Route::patch('user/update', [ProfileController::class, 'updateProfile']);
    Route::delete('user/delete', [ProfileController::class, 'delete']);

    Route::get('logout', [LoginController::class, 'logout']);
    Route::post('users/search', [UsersController::class, 'getMissingUsers']);
});

Route::group(['prefix' => 'chat'], function (){
    Route::group(['middleware' => ['auth:sanctum']], function (){

        Route::get('init', [ChatController::class, 'init']);
        Route::get('user/groups', [GroupController::class, 'getGroupsByUser']);
        Route::post('group/store', [GroupController::class, 'store']);
        Route::get('group/{group_id}/users', [ParticipantsController::class, 'getUsersByGroup']);
        //--------------------------------Role Cache--------------------------------//
        Route::get('role-rules/set', [RoleRuleCachingController::class, 'setAllRules']); // @todo Add admin user and role based middleware
        Route::get('role-rules/get', [RoleRuleCachingController::class, 'getAllRules']);


        Route::group(['middleware' => ['chat_group_access']], function () {
            Route::get('group/{group_id}/leave', [ParticipantsController::class, 'leaveGroup']);
            Route::post('group/remove-user', [ParticipantsController::class, 'removeUserFromGroup']);
            Route::post('group/{group_id}/add-users', [ParticipantsController::class, 'addUsersToGroup']);
            Route::post('group/change-user-role', [ParticipantsController::class, 'chageParticipantsRole']);
            Route::post('group/change-group-type', [GroupController::class, 'chageGroupType']);
        
        
            Route::post('group/change-group-name', [GroupController::class, 'changeGroupName']);
            Route::get('group/{group_id}', [GroupController::class, 'getGroupById']);
        
            Route::get('group/{group_id}/before-msg/{earliest_msg_id}', [MessageController::class, 'getBeforeMessage']);
            Route::get('group/{group_id}/latest-messages',              [MessageController::class, 'getLatestMessages']);
            Route::get('group/{group_id}/from-msg/{latest_msg_id}',     [MessageController::class, 'getMissingMessages']);
        
            Route::group(['prefix' => 'message'], function (){
                Route::post('store', [MessageController::class, 'store'])->middleware(['can_chat']); // @todo middleware logic should be moved to validation
                Route::post('seen', [MessageController::class, 'messageIsSeen']);
                Route::post('delete', [MessageController::class, 'delete']);
                Route::patch('update', [MessageController::class, 'update']);
            });
        });
    });
});


