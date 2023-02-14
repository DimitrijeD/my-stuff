<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Chat\ChatMessage;
use Illuminate\Support\Str;
use App\Events\ChatEvents\MessageEvents\NewChatMessage;
use App\Models\Chat\ChatGroup;
use Illuminate\Support\Facades\Event;
use Tests\Feature\Chat\GroupBuilderTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SubmitMessageTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('files');
        $this->disk = Storage::disk(config('images.user')['disk'] ?? 'public');

        $this->makeGroup();  

        $this->newMessage = [
            'text' => Str::random(3),
            'group_id' => $this->group->id,
            'user_id' => $this->user->id
        ];

        $this->endpoint = '/api/chat/message/store';
    }

    public function test_submit_message_with_files()
    {
        $this->newMessage['files'] = [
            UploadedFile::fake()->create('document.pdf', 123),
            UploadedFile::fake()->image('test.jpg'),
        ];

        $response = $this->post($this->endpoint, $this->newMessage);

        $response->assertStatus(201);
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
        $lastMsgBeforeSubmit = ChatMessage::where(['user_id' => $this->user->id, 'group_id' => $this->group->id])->latest()->first();

        $this->post($this->endpoint, $this->newMessage);

        $lastMsgAfterSubmit = ChatMessage::where(['user_id' => $this->user->id, 'group_id' => $this->group->id])->latest()->first();

        $this
            ->assertDatabaseHas('chat_groups', [
                'id' => $this->group->id,
                'last_msg_id' => $lastMsgAfterSubmit->id,
                'updated_at'  => $lastMsgAfterSubmit->created_at
            ]);
    }

    public function test_submit_message_dispatches_message_event()
    {
        Event::fake();

        $this->post($this->endpoint, $this->newMessage);

        Event::assertDispatched(NewChatMessage::class, function ($e) {
            $latestMessage = ChatMessage::
                where([
                    'user_id' => $this->user->id, 
                    'group_id' => $this->group->id
                ])
                ->with(['files'])
                ->latest()
                ->first();
                
            return $e->data->jsonSerialize() == $latestMessage->jsonSerialize();
        });
    }
}
