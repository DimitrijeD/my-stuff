<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AccountVerification;
use Illuminate\Support\Facades\Hash;

class EmailVerificationTest extends TestCase
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
            'email_verified_at' => null,
            'remember_token' => Str::random(10),
        ]);
        
        $this->data = ['email' => $this->email];

        $this->code = Str::random(AccountVerification::EMAIL_HASH_LENGTH);

        $this->verification = AccountVerification::factory()->create([
            'code'    => Hash::make( $this->code ),
            'type'    => AccountVerification::EMAIL_TYPE,
            'user_id' => $this->user->id,
        ]);

        $this->withHeaders([ 
            'Accept' => 'application/json', 
            'Authorization' => "Bearer {$this->user->createToken('app')->plainTextToken}" 
        ]);
        
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
            'status' => 'error',
            'code' => 404
        ]);
    }

    /**
     * Email verification status
     * 
     * If user hasn't verified email (leaves and later comes back to app), 
     * this route provides "status" value which defines what user should do next to verify email.
     * 
     */
    public function test_if_not_verified_return_status()
    {
        $this->user = User::factory()->create( ['email_verified_at' => null] );
        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");

        $response = $this->get('/api/email-verification/is-validated');

        $response->assertJson([
            "status" => "not_verified",
            "message" => __("Please verify your account."),
            "user" => [
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
        ]);
    }
}
