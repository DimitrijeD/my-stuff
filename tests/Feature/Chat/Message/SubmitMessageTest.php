<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Chat\ChatMessage;
use Illuminate\Support\Str;
use App\Events\ChatEvents\MessageEvents\NewChatMessage;
use Illuminate\Support\Facades\Event;
use Tests\Feature\Chat\GroupBuilderTrait;

class SubmitMessageTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup();  

        $this->newMessage = [
            'text' => Str::random(3),
            'group_id' => $this->group->id,
            'user_id' => $this->user->id
        ];

        $this->endpoint = '/api/chat/message/store';
    }

    public function test_submit_message_saves_it_in_db()
    {
        $this->post($this->endpoint, $this->newMessage);

        $this->assertDatabaseHas('chat_messages', [
            'text' => $this->newMessage['text'],
            'group_id' => $this->newMessage['group_id'],
            'user_id' => $this->newMessage['user_id'],
        ]);
    }

    public function test_submit_message_updates_groups_last_message_it_has_and_updated_field()
    {
        $lastMsgIdBeforeSubmit = ChatMessage::where(['user_id' => $this->user->id, 'group_id' => $this->group->id])->latest()->first();

        $this->post($this->endpoint, $this->newMessage);

        $lastMsgIdAfterSubmit = ChatMessage::where(['user_id' => $this->user->id, 'group_id' => $this->group->id])->latest()->first();
        
        $this
            ->assertDatabaseHas('chat_groups', [
                'id' => $this->group->id,
                'last_msg_id' => $lastMsgIdAfterSubmit->id,
                'updated_at' => now()
            ])
            ->assertTrue($lastMsgIdBeforeSubmit->id < $lastMsgIdAfterSubmit->id);
    }

    public function test_submit_message_dispatches_message_event()
    {
        Event::fake();

        $this->post($this->endpoint, $this->newMessage);

        Event::assertDispatched(NewChatMessage::class, function ($e) {
            $latestMessage = ChatMessage::where(['user_id' => $this->user->id, 'group_id' => $this->group->id])->latest()->first();
            return $e->data->jsonSerialize() == $latestMessage->jsonSerialize();
        });
    }
}
