<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Database\Seeders\ChatGroupClusterSeeder;
use App\Models\ChatMessage;
use App\Http\Response\ApiResponse;

class DeleteMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chatGroupSeeder = (resolve(ChatGroupClusterSeeder::class));
        
        $this->allChatData = $this->chatGroupSeeder->run();

        $this->user      = $this->allChatData['users'][0];
        $this->otherUser = $this->allChatData['users'][1];
        
        $this->group = $this->allChatData['group'];

        $this->userPivot =      $this->allChatData['pivots'][$this->user     ->id];
        $this->otherUserPivot = $this->allChatData['pivots'][$this->otherUser->id];

        $this->targetMessage = ChatMessage::factory()->create([
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
        ]);

        $this->endpoint = "/api/chat/message/delete";
    }

    public function test_only_owner_can_delete_message()
    {
        $this->withHeaders([
            'Authorization' => "Bearer {$this->user->createToken('app')->plainTextToken}"
        ]);

        $response = $this->post($this->endpoint, [
            'message_id' => $this->targetMessage->id,
            'group_id' => $this->group->id,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(
                ApiResponse::success([
                    [[ __('chat.message.deleted') ]],
                ]) 
            );

        $this->assertDatabaseMissing('chat_messages', [
            'id' => $this->targetMessage->id
        ]);
    }


    public function test_someone_cannot_delete_message_message_he_is_not_owner_of()
    {
        $this->withHeaders([
            'Authorization' => "Bearer {$this->otherUser->createToken('app')->plainTextToken}"
        ]);

        $response = $this->post($this->endpoint, [
            'message_id' => $this->targetMessage->id,
            'group_id' => $this->group->id,
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([ 
                "messages" => [ 'message_id' => [ __('model.messageNotFound') ]],
                "response_type" => "error"
            ]);

        $this->assertDatabaseHas('chat_messages', [
            'id' => $this->targetMessage->id
        ]);
    }
}
