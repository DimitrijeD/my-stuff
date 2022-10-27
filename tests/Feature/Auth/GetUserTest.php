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
        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");
        
        $response = $this->get($this->getUserEndpoint);

        $response->assertJson([
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name'  => $this->user->last_name,
            'email_verified_at' => $this->user->email_verified_at->jsonSerialize(),
            'updated_at'        => $this->user->updated_at->jsonSerialize(),
            'created_at'        => $this->user->created_at->jsonSerialize(),
        ]);
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
