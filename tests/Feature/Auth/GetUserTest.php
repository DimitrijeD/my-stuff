<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class GetUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getUserEndpoint = "/api/user";
    }

    /**
     * Requires user has email verified. 
     */
    public function test_gets_loggedin_verified_user()
    {
        $this->user = User::factory()->create( ['email_verified_at' => now()] );
        $this->user->userSetting()->create();

        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");
        
        $response = $this->get($this->getUserEndpoint);

        $response->assertJson( $this->user->jsonSerialize());
    }

    /**
     * If no/invalid token, returns 401. 
     */
    public function test_guest_401()
    {
        $response = $this->get($this->getUserEndpoint);

        $response->assertStatus(401);
    }
}
