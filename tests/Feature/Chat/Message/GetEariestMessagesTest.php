<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;
use App\MyStuff\Repos\ChatMessage\ChatMessageEloquentRepo;

class GetEariestMessagesTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->chatMessageRepo = resolve(ChatMessageEloquentRepo::class);

        $this->makeGroup();  
        
        $this->oldest_msg_id_user_has = $this->chatSeeder->group->lastMessage->id;

        $this->endpoint = "api/chat/message/before-message?group_id={$this->group_id}&earliest_msg_id={$this->oldest_msg_id_user_has}";
    }

    public function test_gets_older_messages()
    {
        $response = $this->get($this->endpoint);

        $response->assertJson($this->chatMessageRepo->getBeforeMessage($this->group_id, $this->oldest_msg_id_user_has)->jsonSerialize());
    }
}
