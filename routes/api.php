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
use App\Http\Controllers\Chat\RoleRuleCachingController;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorageController;


Route::group(['middleware' => ['guest', 'throttle:10,1']], function () {  
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login',           [LoginController::class, 'login']);
    Route::post('forgot-password', [LoginController::class, 'forgotPasswordGiveMeEmail']);
});

Route::group(['middleware' => ['throttle:10,1']], function () {  
    Route::post('reset-password', [LoginController::class, 'resetPassword']);
});

Route::group(['prefix' => 'email-verification', 'middleware' => ['throttle:10,1', 'auth:sanctum']], function () {
    Route::post('create-or-update',       [AccountVerificationController::class, 'createOrUpdateForEmail']);
    Route::get ('uid/{user_id}/c/{code}', [AuthenticationController::class, 'emailVerificationAttempt'   ]);
    Route::get ('/is-validated',          [AuthenticationController::class, 'isUserValidated'            ]);
});


Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('user',           [ProfileController::class, 'user']);
    Route::patch('user/update',  [ProfileController::class, 'updateProfile']);
    Route::delete('user/delete', [ProfileController::class, 'delete']);
    
    Route::get('logout', [LoginController::class, 'logout']);

    Route::post('users/search', [UsersController::class, 'getMissingUsers']);
});

Route::group(['prefix' => 'chat', 'middleware' => ['auth:sanctum']], function (){
    Route::get('init', [ChatController::class, 'init']);

    Route::get('user/groups',  [GroupController::class, 'getGroupsByUser']);
    Route::post('group/store', [GroupController::class, 'store'          ]);

    Route::get('group/{group_id}/users', [ParticipantsController::class, 'getUsersByGroup']);

    Route::get('role-rules/set', [RoleRuleCachingController::class, 'setAllRules']); // @todo Add admin user and role based middleware
    Route::get('role-rules/get', [RoleRuleCachingController::class, 'getAllRules']);

    Route::group(['middleware' => ['chat_group_access']], function () {
        Route::get("/files/{any}", [StorageController::class, 'getFile'])->where('any', '.*');

        Route::group(['prefix' => 'group'], function (){
            Route::get ('{group_id}/leave',     [ParticipantsController::class, 'leaveGroup'               ]);
            Route::post('remove-user',          [ParticipantsController::class, 'removeUserFromGroup'      ]);
            Route::post('{group_id}/add-users', [ParticipantsController::class, 'addUsersToGroup'          ]);
            Route::post('change-user-role',     [ParticipantsController::class, 'chageParticipantsRole'    ]);
            Route::post('invitation-response',  [ParticipantsController::class, 'usersResponseToInvitation']);

            Route::post('change-group-type', [GroupController::class, 'chageGroupType' ]);
            Route::post('change-group-name', [GroupController::class, 'changeGroupName']);
            Route::get ('{group_id}',        [GroupController::class, 'getGroupById'   ]);
        });

        Route::group(['prefix' => 'message'], function (){
            Route::post('store',   [MessageController::class, 'store'])->middleware(['can_chat']); 
            Route::post('seen',    [MessageController::class, 'messageIsSeen']);
            Route::post('delete',  [MessageController::class, 'delete']);
            Route::patch('update', [MessageController::class, 'update']);

            Route::get('before-message', [MessageController::class, 'getBeforeMessage' ]); 
            Route::get('latest-messages', [MessageController::class, 'getLatestMessages' ]);
            Route::get('from-messages', [MessageController::class, 'getMissingMessages']);

        });
    });
});

