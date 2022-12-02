<?php

namespace Tests\Feature\Chat\Participants\Leave;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Response\ApiResponse;
use Tests\Feature\Chat\GroupBuilderTrait;

class ParticipantCanLeaveGroupTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->makeGroup(); 

        $this->endpoint = "/api/chat/group/{$this->group->id}/leave";
    }

    public function test_can_leave_group()
    {
        $response = $this->get($this->endpoint);

        $response->assertJson(ApiResponse::info([
            'messages' => [[ __('chat.participants.leave.success') ]],
        ]));

        $this->assertDatabaseMissing('group_participants', [
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('chat_groups', [
            'id' => $this->group->id,
        ]);
    }

}
