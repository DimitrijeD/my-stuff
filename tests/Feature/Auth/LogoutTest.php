<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->logoutEndpoint = '/api/logout';
    }

    public function test_logout_deletes_token()
    {
        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");

        $response = $this->get($this->logoutEndpoint);

        $this->assertTrue($this->user->tokens->isEmpty());

        $response->assertJson([
            'messages' => [
                'success' => [__('auth.logout')]
            ]
        ]);
    }

    public function test_guest()
    {
        $response = $this->get($this->logoutEndpoint);

        $response->assertJson([ 
            "messages" => [[ "You must be logged in." ]],
            "response_type" => "error"
        ]);
    }
}
