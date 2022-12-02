<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;
use App\MyStuff\Repos\ChatMessage\ChatMessageEloquentRepo;

class GetLatestMessagesTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->chatMessageRepo = resolve(ChatMessageEloquentRepo::class);
        
        $this->makeGroup();

        $this->endpoint = "api/chat/message/latest-messages?group_id={$this->group_id}";
    }

    public function test_gets_latest_messages()
    {
        $response = $this->get($this->endpoint);

        $response->assertJson( $this->chatMessageRepo->getLatestMessages($this->group_id)->jsonSerialize() );
    }
}
