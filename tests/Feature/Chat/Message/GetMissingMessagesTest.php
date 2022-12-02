<?php

namespace Tests\Feature\Chat\Message;

use App\Models\Chat\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;
use App\MyStuff\Repos\ChatMessage\ChatMessageEloquentRepo;

class GetMissingMessagesTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatMessageRepo = resolve(ChatMessageEloquentRepo::class);

        $this->makeGroup();

        $this->last_message_id_user_has = $this->chatSeeder->group->lastMessage->id;

        ChatMessage::factory()->create([
            'group_id' => $this->group_id
        ]);
        
        $this->endpoint = "/api/chat/message/from-messages?latest_msg_id={$this->last_message_id_user_has}&group_id={$this->group_id}";
    }

    /**
     * Request should return only one message 
     */
    public function test_get_only_one_missing_message()
    {
        $response = $this->get($this->endpoint);

        $response->assertJson($this->chatMessageRepo->getMissingMessages($this->group_id, $this->last_message_id_user_has)->jsonSerialize());
    }
}
