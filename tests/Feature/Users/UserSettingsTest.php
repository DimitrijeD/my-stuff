<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserSettings;
use App\Http\Response\ApiResponse;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->userSetting()->create();

        $this->form = [
            'settingsFiels' => [
                'open_all_chats_on_new_message' => !UserSettings::OPEN_ALL_CHATS_ON_NEW_MESSAGE,
                'show_only_important_notifications' => !UserSettings::SHOW_ONLY_IMPORTANT_NOTIFICATIONS,
                'theme' => UserSettings::DEFAULT_THEME,
            ],
            'userFields' => [
                'first_name' => 'sssssssssssssssssssss',
                'last_name' => 'Snakjsak',
            ]
        ];

        $this->withHeader( 'Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}" );
        
        $this->apiEndpoint = "/api/user/update";
    }
    
    public function test_can_update_all_available_options()
    {
        $response = $this->patch($this->apiEndpoint, $this->form);
        
        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => User::where('email', $this->user->email)->with(['userSetting'])->first()->jsonSerialize()
                ]
            ]) 
        );
    }

    public function test_can_update_only__first_name()
    {
        $response = $this->patch($this->apiEndpoint, [
            'userFields' => [
                'first_name' => 'sssssssssssssssssssss',
            ]
        ]);
        
        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => User::where('email', $this->user->email)->with(['userSetting'])->first()->jsonSerialize()
                ]
            ]) 
        );
    }

    public function test_can_update_only__last_name()
    {
        $response = $this->patch($this->apiEndpoint, [
            'userFields' => [
                'last_name' => 'sssssssssssssssssssss',
            ]
        ]);
        
        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => User::where('email', $this->user->email)->with(['userSetting'])->first()->jsonSerialize()
                ]
            ]) 
        );
    }

    public function test_can_update_only__open_all_chats_on_new_message()
    {
        $response = $this->patch($this->apiEndpoint, [
            'settingsFiels' => [
                'open_all_chats_on_new_message' => !UserSettings::OPEN_ALL_CHATS_ON_NEW_MESSAGE,
            ],
        ]);
        
        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => User::where('email', $this->user->email)->with(['userSetting'])->first()->jsonSerialize()
                ]
            ]) 
        );
    }

    public function test_can_update_only__show_only_important_notifications()
    {
        $response = $this->patch($this->apiEndpoint, [
            'settingsFiels' => [
                'show_only_important_notifications' => !UserSettings::SHOW_ONLY_IMPORTANT_NOTIFICATIONS,
            ],
        ]);
        
        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => User::where('email', $this->user->email)->with(['userSetting'])->first()->jsonSerialize()
                ]
            ]) 
        );
    }

    public function test_can_update_only__theme()
    {
        $response = $this->patch($this->apiEndpoint, [
            'settingsFiels' => [
                'theme' => UserSettings::DEFAULT_THEME,
            ],
        ]);
        
        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.updateSuccess') ]],
                'data' => [
                    'user' => User::where('email', $this->user->email)->with(['userSetting'])->first()->jsonSerialize()
                ]
            ]) 
        );
    }
}
