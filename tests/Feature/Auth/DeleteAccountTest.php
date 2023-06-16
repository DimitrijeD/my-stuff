<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\UserSettings;
use App\Http\Response\ApiResponse;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\MyStuff\Storage\ImageStorage;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->user->userSetting()->create();

        $this->withHeader( 'Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}" );
        
        $this->endpoint = "/api/user/delete";
    }

    public function test_user_can_delete_his_acc()
    {
        $response = $this->delete($this->endpoint);

        $response->assertStatus(200)->assertJson(
            ApiResponse::success([
                'messages' => [[ __('profile.youDeletedAcc') ]],
            ]) 
        );
    }

    public function test_cannot_delete_without_token()
    {
        $this->withHeader( 'Authorization', "Bearer ");

        $response = $this->delete($this->endpoint);
        
        $response->assertJson([ 
            "messages" => [[ "You must be logged in." ]],
            "response_type" => "error"
        ]);
    }

    public function test_will_delete_profile_images()
    {
        $this->user->makeProfileImages(UploadedFile::fake()->image('avatar.jpg'));
        $this->user->save();
        
        $this->delete($this->endpoint);

        $disk = Storage::disk(config('images.user')['disk'] ?? 'public');
        $disk->assertMissing( (new ImageStorage(config('images.user.image'    )))->getPathFromUrl($this->user->image    ) );
        $disk->assertMissing( (new ImageStorage(config('images.user.thumbnail')))->getPathFromUrl($this->user->thumbnail) );
    }
}
