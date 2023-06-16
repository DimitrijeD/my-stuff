<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Factories\UserFactory;
use App\Http\Response\ApiResponse;
use App\MyStuff\Storage\ImageStorage;

class RegisterSuccessTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('avatars');

        $this->disk = Storage::disk(config('images.user')['disk'] ?? 'public');

        $this->userFormData = [
            'first_name' => UserFactory::getDefUser()['first_name'],
            'last_name'  => UserFactory::getDefUser()['last_name'],
            'email'      => UserFactory::getDefUser()['email'],
            'password'              => UserFactory::getDefUser()['password'],
            'password_confirmation' => UserFactory::getDefUser()['password'],
            'profilePicture' => UploadedFile::fake()->image('avatar.jpg'),
        ];
        
        $this->registerEndpoint = '/api/register';
    }

    public function test_user_stored()
    {
        $response = $this->post($this->registerEndpoint, $this->userFormData);
        
        $this->user = User::find($response->json()['data']['user']['id']);
        
        $this->assertDatabaseHas('users', [
            'email' => $this->userFormData['email'],
        ]);

        $this->user->deleteProfileImages();
    }

    public function test_user_register_gets_json()
    {
        $response = $this->post($this->registerEndpoint, $this->userFormData);
             
        $response->assertStatus(201);

        $this->user = User::find($response->json()['data']['user']['id']);

        $response->assertJsonFragment(ApiResponse::success([
            'messages' => [ [__('auth.registered') ]],
        ]));

        $response->assertJsonStructure([
            'messages' => [],
            'data' => ['user', 'token'],
            'response_type'
        ]);

        $this->user->deleteProfileImages();
    }

    public function test_image_can_be_jpg()
    {
        $this->userFormData['profilePicture'] = UploadedFile::fake()->image('avatar.jpg')->size(1000);

        $response = $this->post($this->registerEndpoint, $this->userFormData);
        
        $this->user = User::find($response->json()['data']['user']['id']);
        
        $response->assertStatus(201);

        $this->user->deleteProfileImages();
    }

    public function test_image_can_be_png()
    {
        $this->userFormData['profilePicture'] = UploadedFile::fake()->image('avatar.png')->size(1000);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $this->user = User::find($response->json()['data']['user']['id']);

        $response->assertStatus(201);

        $this->user->deleteProfileImages();
    }

    public function test_image_can_be_jpeg()
    {
        $this->userFormData['profilePicture'] = UploadedFile::fake()->image('avatar.jpeg')->size(1000);
        
        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $this->user = User::find($response->json()['data']['user']['id']);

        $response->assertStatus(201);

        $this->user->deleteProfileImages();
    }

    public function test_provided_image_is_stored()
    {
        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $this->user = User::find($response->json()['data']['user']['id']);
       
        $this->disk->assertExists( (new ImageStorage(config('images.user.image'    )))->getPathFromUrl($this->user->image    ) );
        $this->disk->assertExists( (new ImageStorage(config('images.user.thumbnail')))->getPathFromUrl($this->user->thumbnail) );

        $this->user->deleteProfileImages();
    }

    public function test_if_image_not_submitted_can_still_register()
    {
        unset($this->userFormData['profilePicture']);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $this->user = User::find($response->json()['data']['user']['id']);

        $response->assertStatus(201);

        $this->user->deleteProfileImages();
    }
}
