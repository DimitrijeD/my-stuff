<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;
use App\Http\Response\ApiResponse;
use App\Models\Chat\ChatMessage;

class DeleteMessageTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup();  

        $this->newMessage = ChatMessage::factory()->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);

        $this->payload = [
            'message_id' => $this->newMessage->id,
            'group_id' => $this->group->id,
        ];

        $this->endpoint = "/api/chat/message/delete";
    }

    public function test_only_owner_can_delete_message()
    {
        $response = $this->post($this->endpoint, $this->payload);

        $response
            ->assertStatus(200)
            ->assertJson(
                ApiResponse::success([
                    [[ __('chat.message.deleted') ]],
                ]) 
            );

        $this->assertDatabaseMissing('chat_messages', [
            'id' => $this->payload['message_id']
        ]);
    }
}
