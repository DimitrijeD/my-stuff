<?php

namespace Tests\Feature\Chat\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ChatGroup;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\ParticipantPivot;
use App\Models\ChatRole;

use Illuminate\Support\Str;

class RolesSubmitMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->participant = User::factory()->create();
        $this->group = ChatGroup::factory()->create();
        
        $this->storeMessageEndpoint = '/api/chat/message/store';
    }

    private function dataSetup($role)
    {
        $this->userPivot = ParticipantPivot::factory()->create([
            'user_id' => $this->user->id, 
            'group_id' => $this->group->id, 
            'participant_role' => $role,
            'last_message_seen_id' => (ChatMessage::factory()->create([
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
            ]))->id, 
        ]);

        $this->participantPivot = ParticipantPivot::factory()->create([
            'user_id' => $this->participant->id, 
            'group_id' => $this->group->id, 
            'participant_role' => ChatRole::PARTICIPANT,
            'last_message_seen_id' => (ChatMessage::factory()->create([
                'group_id' => $this->group->id,
                'user_id' => $this->participant->id,
            ]))->id, 
        ]);
        
        $this->withHeaders([
            'Authorization' => "Bearer {$this->user->createToken('app')->plainTextToken}"
        ]);

        $this->userFormData = [
            'text' => Str::random(10),
            'group_id' => $this->group->id,
            'user_id' => $this->user->id
        ];
    }

    public function test_participant_can_submit_message()
    {
        $this->dataSetup(ChatRole::PARTICIPANT);

        $response = $this->post($this->storeMessageEndpoint, $this->userFormData);

        $response->assertJsonStructure([
            "user_id", "group_id", "text", "updated_at", "created_at", "id"
        ]);
    }

    public function test_moderator_can_submit_message()
    {
        $this->dataSetup(ChatRole::MODERATOR);

        $response = $this->post($this->storeMessageEndpoint, $this->userFormData);

        $response->assertJsonStructure([
            "user_id", "group_id", "text", "updated_at", "created_at", "id"
        ]);
    }

    public function test_creator_can_submit_message()
    {
        $this->dataSetup(ChatRole::CREATOR);

        $response = $this->post($this->storeMessageEndpoint, $this->userFormData);

        $response->assertJsonStructure([
            "user_id", "group_id", "text", "updated_at", "created_at", "id"
        ]);
    }

    public function test_listener_cannot_submit_message()
    {
        $this->dataSetup(ChatRole::LISTENER);

        $response = $this->post($this->storeMessageEndpoint, $this->userFormData);

        $response->assertJson([
            'error' => __("You cannot chat in this chat group, but you can still see messages"),
        ]);
    }
}
