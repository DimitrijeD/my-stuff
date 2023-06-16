<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Factories\UserFactory;

class RegisterFailTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('avatars');

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

    public function test__required__first_name()
    {
        $this->userFormData['first_name'] = '';

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'first_name' => [__('validation.required', ['attribute' => 'first name'])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__required__last_name()
    {
        $this->userFormData['last_name'] = '';

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'last_name' => [__('validation.required', ['attribute' => 'last name'])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__required__email()
    {
        $this->userFormData['email'] = '';

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'email' => [__('validation.required', ['attribute' => 'email'])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__required__password()
    {
        $this->userFormData['password'] = '';

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'password' => [__('validation.required', ['attribute' => 'password'])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__required__password_confirmation()
    {
        $this->userFormData['password_confirmation'] = '';

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'password' => [__('validation.confirmed', ['attribute' => 'password'])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__first_name__max()
    {
        $this->userFormData['first_name'] = Str::random(256);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'first_name' => [__('validation.max.string', ['attribute' => 'first name', 'max' => 255])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__first_name__min()
    {
        $this->userFormData['first_name'] = Str::random(2);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'first_name' => [__('validation.min.string', ['attribute' => 'first name', 'min' => 3])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__last_name__max()
    {
        $this->userFormData['last_name'] = Str::random(256);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'last_name' => [__('validation.max.string', ['attribute' => 'last name', 'max' => 255])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__last_name__min()
    {
        $this->userFormData['last_name'] = Str::random(2);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'last_name' => [__('validation.min.string', ['attribute' => 'last name', 'min' => 3])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__email__max()
    {
        $this->userFormData['email'] = 'w@' . Str::random(254);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'email' => [__('validation.max.string', ['attribute' => 'email', 'max' => 255])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__email__min()
    {
        $this->userFormData['email'] = Str::random(2);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'email' => [__('validation.min.string', ['attribute' => 'email', 'min' => 3])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__password__max()
    {
        $this->userFormData['password'] = Str::random(256);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'password' => [__('validation.max.string', ['attribute' => 'password', 'max' => 255])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__password__min()
    {
        $this->userFormData['password'] = Str::random(5);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'password' => [__('validation.min.string', ['attribute' => 'password', 'min' => 6])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test__user_with_this_email_already_exists()
    {
        User::factory()->create(['email' => $this->userFormData['email']]);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'email' => [__('validation.unique', ['attribute' => 'email'])],
            ],
            'response_type' => 'error'
        ]);
    }

    public function test_image_greater_than_max_size()
    {
        $maxSize = \App\Http\Requests\Auth\RegisterRequest::MAX_IMAGE_SIZE;

        $this->userFormData['profilePicture'] = UploadedFile::fake()->image('avatar.jpeg')->size($maxSize + 1);

        $response = $this->post($this->registerEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [
                'profilePicture' => [__('validation.max.file', ['attribute' =>'profile picture', 'max' => $maxSize ])],
            ],
            'response_type' => 'error'
        ]);
    }
}
