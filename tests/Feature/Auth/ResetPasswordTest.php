<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Auth\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Factories\UserFactory;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->email = UserFactory::getDefUser()['email'];
        $this->password = UserFactory::getDefUser()['password'];
        $this->newPassword = $this->password . 's';

        $this->token = Str::random(PasswordReset::EMAIL_HASH_LENGTH);

        $this->user = User::factory()->create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->passwordReset =  PasswordReset::factory()->create([
            'email' => $this->email,
            'token' => Hash::make($this->token),
            'attempts' => 1
        ]);

        $this->form = [
            'email' => $this->email,
            'token' => $this->token,
            'password'              => $this->newPassword,
            'password_confirmation' => $this->newPassword
        ];

        $this->endpoint = '/api/reset-password';
    }
    
    public function test_unauth_user_resets_password_successfully()
    {
        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' => __('passwords.success_reset_unathenticated'),
            'response_type' => 'success',
        ]);
    }
        
    public function test_auth_user_resets_password_successfully()
    {
        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");

        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' =>  __('passwords.success_reset_authenticated'),
            'response_type' => 'success',
        ]);
    }

    public function test_wrong_token()
    {
        $this->form['token'] = $this->form['token'] . 's';

        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' => [
                'error' => [__('passwords.VE007')]
            ],
            'response_type' => 'error'
        ]);
    }

    public function test_user_with_email_doesnt_exist()
    {
        $this->form['email'] = $this->form['email'] . 's';

        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' => [
                'error' => [__('passwords.VE006')]
            ],
            'response_type' => 'error'
        ]);
    }

    public function test_deletes_password_reset_record()
    {
        $response = $this->post($this->endpoint, $this->form);

        $this->assertDatabaseMissing('password_resets', [
            'email' => $this->email
        ]);
    }

    public function test_will_update_password_correctly()
    {
        $response = $this->post($this->endpoint, $this->form);
        
        $this->assertTrue(
            Hash::check($this->newPassword, $this->user->fresh()->password)
        );
    }

    public function test_old_password_invalid()
    {
        $response = $this->post($this->endpoint, $this->form);
        
        $this->assertFalse(
            Hash::check($this->password, $this->user->fresh()->password)
        );
    }


    public function test_invalid_data()
    {
        $this->form['email'] = null;
        $this->form['token'] = null;
        $this->form['password'] = null;

        $response = $this->post($this->endpoint, $this->form);

        $response->assertJson([
            'messages' => [
                'email' => [__('passwords.VE003')],
                "password" => [ "The password field is required." ],
                "token" => [ __('passwords.VE001') ],
            ],
            'response_type' => 'error'
        ]);
    }
}
