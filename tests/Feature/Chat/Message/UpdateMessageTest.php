<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Database\Seeders\ChatGroupClusterSeeder;

class UpdateMessageTest extends TestCase
{
    use RefreshDatabase;

    // @todo only 1 test case covered

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatGroupSeeder = (resolve(ChatGroupClusterSeeder::class));
        
        $this->allChatData = $this->chatGroupSeeder->run();

        $this->user = $this->allChatData['users'][0];
        
        $this->group = $this->allChatData['group'];

        $this->targetMessage = ChatMessage::factory()->create([
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
        ]);

        $this->form = [
            'group_id' => $this->group->id,
            'message_id' => $this->targetMessage->id,
            'text' => Str::random(5),
        ];

        $this->withHeaders([
            'Authorization' => "Bearer {$this->user->createToken('app')->plainTextToken}"
        ]);

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
