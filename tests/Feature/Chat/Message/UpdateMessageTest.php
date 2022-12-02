<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Chat\ChatMessage;
use Illuminate\Support\Str;
use Tests\Feature\Chat\GroupBuilderTrait;

class UpdateMessageTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup();  

        $this->targetMessage = ChatMessage::factory()->create([
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
        ]);

        $this->form = [
            'group_id' => $this->group->id,
            'message_id' => $this->targetMessage->id,
            'text' => Str::random(5),
        ];

        $this->messageStructure = [
            "id", "user_id", "group_id", "text", "updated_at", "created_at", "edited"
        ];

        $this->endpoint = '/api/chat/message/update';
    }

    public function test_can_edit_message_text()
    {
        $response = $this->patch($this->endpoint, $this->form);
        
        $response->assertStatus(200);
    }
}
