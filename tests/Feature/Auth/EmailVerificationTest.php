<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Auth\AccountVerification;
use Illuminate\Support\Facades\Hash;
use Database\Factories\UserFactory;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->email =  UserFactory::getDefUser()['email'];
        $this->password = UserFactory::getDefUser()['password'];

        $this->user = User::factory()->create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => null,
        ]);
        
        $this->data = ['email' => $this->email];

        $this->code = Str::random(AccountVerification::EMAIL_HASH_LENGTH);

        $this->verification = AccountVerification::factory()->create([
            'code'    => Hash::make( $this->code ),
            'type'    => AccountVerification::EMAIL_TYPE,
            'user_id' => $this->user->id,
        ]);

        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");
        
        $this->requestEmailToVerifyAccount = '/api/email-verification/create-or-update';
        $this->attemptEndpoint = "/api/email-verification/uid/{$this->user->id}/c/{$this->code}";
    }

    public function test_loggedin_user_requests_another_email()
    {
        $response = $this->post($this->requestEmailToVerifyAccount, []);

        $response->assertOk();
    }

    public function test_success()
    {
        $response = $this->get($this->attemptEndpoint);

        $response->assertOk();
    }

    public function test_already_verified()
    {
        $this->user->email_verified_at = now();
        $this->user->save();
        $this->user->fresh();

        $response = $this->get($this->attemptEndpoint);
        
        $response->assertOk();
    }

    /**
     * If for what ever reason AccountVerification for verified user exists in database, it should be deleted
     */
    public function test_already_verified_deletes_verification()
    {
        $this->user->email_verified_at = now();
        $this->user->save();
        $this->user->fresh();

        $this->get($this->attemptEndpoint);
        
        $this->assertDatabaseMissing('account_verifications', [
            'type' => AccountVerification::EMAIL_TYPE,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * In order to successfully verify email, user must provide correct @param $code in URL
     */
    public function test_incorrect_code()
    {
        $code = $this->code . 'sasdasd';
        $Endpoint = "/api/email-verification/uid/{$this->user->id}/c/{$code}";

        $response = $this->get($Endpoint);
       
        $response->assertJson([
            'messages' => [ [ __("email.email_verification.incorrect_hash") ] ],
        ]);
    }

    /**
     * Email verification status
     * 
     * If user hasn't verified email (leaves and later comes back to app), 
     * this route provides "status" value which defines what user should do next to verify email.
     * 
     */
    public function test_not_verified_is_pending_verification()
    {
        $response = $this->get('/api/email-verification/is-validated');

        $response->assertJson([
            'response_type' => 'success',
            'messages' => [[__("email.email_verification.pending_verification")]],
            'data' => [
                'status' => 'not_verified',
                'user' => [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name ,
                    'last_name' => $this->user->last_name,
                    'email' => $this->user->email ,
                    'image' => $this->user->image ,
                    'thumbnail' => $this->user->thumbnail ,
                    'email_verified_at' => $this->user->email_verified_at,
                    'created_at' => $this->user->created_at->jsonSerialize(),
                    'updated_at' => $this->user->updated_at->jsonSerialize(),
                ],
            ]
        ]);
    }
}
