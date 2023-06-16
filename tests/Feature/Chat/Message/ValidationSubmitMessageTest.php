<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\Feature\Chat\GroupBuilderTrait;

class ValidationSubmitMessageTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup();

        $this->userFormData = [
            'text' => 'ads',
            'group_id' => $this->group->id,
            'user_id' => $this->user->id
        ];

        $this->storeMessageEndpoint = '/api/chat/message/store';
    }

    public function test_text_is_too_long()
    {
        $this->userFormData['text'] = Str::random(1001);

        $response = $this->post($this->storeMessageEndpoint, $this->userFormData);

        $response->assertStatus(422)->assertJson([
            'messages' => [ "text" => [__("The text must not be greater than 1000 characters.")] ],
            'response_type' => 'error'
        ]);
    }
}
