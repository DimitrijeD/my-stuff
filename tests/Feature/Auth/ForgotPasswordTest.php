<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Auth\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Factories\UserFactory;

class ForgotPasswordTest extends TestCase
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

        $this->form = [
            'email' => $this->email,
        ];

        $this->endpoint = '/api/forgot-password';
    }
    
    public function test_user_create_new_password_reset_successfully()
    {
        $response = $this->post($this->endpoint, $this->form);
        
        $response->assertJson([
            'messages' => [[ __('auth.forgot_password_email.success', ['email' => $this->email]) ]],
            'response_type' => 'success'
        ]);
    }

    public function test_user_can_update_password_reset_successfully()
    {
        $token = Str::random(PasswordReset::EMAIL_HASH_LENGTH);

        PasswordReset::factory()->create([
            'email' => $this->email,
            'token' => Hash::make($token),
            'attempts' => 2
        ]);

        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' => [[ __('auth.forgot_password_email.update', ['email' => $this->email]) ]],
            'response_type' => 'success'
        ]);
    }


    public function test_user_can_not_update_password_reset_if_exceeded_max_attempts()
    {
        $token = Str::random(PasswordReset::EMAIL_HASH_LENGTH);

        PasswordReset::factory()->create([
            'email' => $this->email,
            'token' => Hash::make($token),
            'attempts' => PasswordReset::MAX_REQUESTS + 1
        ]);

        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' => [[ __('auth.forgot_password_email.max_requests_exceeded', ['email' => $this->email]) ]],
            'response_type' => 'error'
        ]);
    }
}
