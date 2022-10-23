<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->email = 'qwe@qwe';
        $this->password = 'qweqweqweQ1';

        $this->user = User::factory()->create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->withHeaders([ 'Accept' => 'application/json', ]);

        $this->userFormData = [
            'email' => $this->email,
            'password' => $this->password,
        ];
        
        $this->loginEndpoint = '/api/login';
    }

    public function test_user_can_login_while_returning_user_obj_and_token()
    {
        $response = $this->post($this->loginEndpoint, $this->userFormData);

        $response->assertJsonStructure([
            'user', 'token',
        ]);
    }

    public function test_incorrect_email()
    {
        $this->userFormData['email'] = $this->userFormData['email'] . 's';

        $response = $this->post($this->loginEndpoint, $this->userFormData);
        
        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => __("The provided credentials are incorrect."),
                "errors" => [
                    "email" => [__("The provided credentials are incorrect.")]
                ]
            ]); 
    }

    public function test_incorrect_password()
    {
        $this->userFormData['password'] = $this->userFormData['password'] . 's';

        $response = $this->post($this->loginEndpoint, $this->userFormData);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => __("The provided credentials are incorrect."),
                "errors" => [
                    "email" => [__("The provided credentials are incorrect.")]
                ]
            ]); 
    }

    public function test_password_case_missmatch()
    {
        $this->userFormData['password'] = ucfirst($this->userFormData['password']);

        $response = $this->post($this->loginEndpoint, $this->userFormData);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => __("The provided credentials are incorrect."),
                "errors" => [
                    "email" => [__("The provided credentials are incorrect.")]
                ]
            ]); 
    }

}
