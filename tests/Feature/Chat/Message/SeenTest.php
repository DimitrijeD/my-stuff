<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

use App\Models\Chat\ChatMessage;
use App\Events\ChatEvents\MessageEvents\MeSawMessage;
use Tests\Feature\Chat\GroupBuilderTrait;

class SeenTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup();  

        $this->newMessage = ChatMessage::factory()->create([
            'group_id' => $this->group_id,
            'user_id' => $this->otherUser->id,
        ]);

        $this->payload = [
            'group_id' => $this->group_id,
            'message_id_saw' => $this->newMessage->id,
        ];

        $this->endpoint = "/api/chat/message/seen";
    }

    public function test_updates_pivot()
    {
        $this->post($this->endpoint, $this->payload);

        $this->assertDatabaseHas('group_participants', [
            'user_id' => $this->user->id,
            'group_id' => $this->group_id,
            'last_message_seen_id' => $this->payload['message_id_saw'],
        ]);
    }

    public function test_dispatches_seen_even()
    {
        Event::fake();

        $this->post($this->endpoint, $this->payload);

        Event::assertDispatched(MeSawMessage::class, function ($e) {
            return $e->data->group_id == $this->group_id 
                && $e->data->user_id == $this->user->id
                && $e->data->last_message_seen_id == $this->payload['message_id_saw'];
        });
    }
}
