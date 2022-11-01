<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Factories\UserFactory;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->email = UserFactory::getDefUser()['email'];
        $this->password = UserFactory::getDefUser()['password'];

        $this->user = User::factory()->create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->userFormData = [
            'email' => $this->email,
            'password' => $this->password,
        ];
        
        $this->loginEndpoint = '/api/login';
    }

    public function test_user_can_login_while_returning_user_obj_and_token()
    {
        $response = $this->post($this->loginEndpoint, $this->userFormData);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'messages' => [ 'success' => [__('auth.loggedin')] ],
        ]);

        $response->assertJsonStructure([
            'messages' => [],
            'data' => ['user', 'token'],
            'response_type'
        ]);
    }

    public function test_incorrect_email()
    {
        $this->userFormData['email'] = $this->userFormData['email'] . 's';

        $response = $this->post($this->loginEndpoint, $this->userFormData);
        
        $response->assertStatus(422)->assertJson([
            'messages' => [
                "email" => [__("The provided credentials are incorrect.")]
            ],
            'response_type' => 'error'
        ]);
    }

    public function test_incorrect_password()
    {
        $this->userFormData['password'] = $this->userFormData['password'] . 's';

        $response = $this->post($this->loginEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                "email" => [__("The provided credentials are incorrect.")]
            ],
            'response_type' => 'error'
        ]);
    }

    public function test_password_case_missmatch()
    {
        $this->userFormData['password'] = ucfirst($this->userFormData['password']);

        $response = $this->post($this->loginEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                "email" => [__("The provided credentials are incorrect.")]
            ],
            'response_type' => 'error'
        ]);
    }

}
