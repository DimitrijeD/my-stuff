<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\UserSettings;
use App\Http\Response\ApiResponse;
use Tests\TestCase;

class UserDeleteSelfTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->userSetting()->create();

        $this->withHeader( 'Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}" );
        
        $this->apiEndpoint = "/api/user/delete";
    }

    public function test_user_can_delete_his_acc()
    {
        $response = $this->delete($this->apiEndpoint);

        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.youDeletedAcc') ]],
            ]) 
        );
    }

    public function test_cannot_delete_without_token()
    {
        $this->withHeader( 'Authorization', "Bearer ");

        $response = $this->delete($this->apiEndpoint);
        
        $response->assertJson([ 
            "messages" => [[ "You must be logged in." ]],
            "response_type" => "error"
        ]);
    }
}
