<?php

namespace Tests\Feature\Chat\Participants\Remove;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;
use App\Models\Chat\ChatGroup;
use App\Http\Response\ApiResponse;

class RoleRemoveRoleTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeGroupWith([
            'model_type' => ChatGroup::TYPE_PUBLIC_OPEN,
        ]);
    }

    public function test_creator_remove_participant_from_group()
    {
        $response = $this->post("/api/chat/group/remove-user", [
            'group_id' => $this->group->id,
            'remove_user_id' => $this->otherUser->id
        ]);

        $response->assertJson(ApiResponse::info([
            'messages' => [[ __('chat.participants.remove.success') ]],
        ]));

        // after request finishes, check if that participant was deleted
        $this->assertDatabaseMissing('group_participants', [
            'group_id' => $this->group->id,
            'user_id' => $this->otherUser->id,
        ]);

        // now make sure user wasn't deleted because if this happends, There is massive issue with code... :/
        $this->assertDatabaseHas('users', [
            'id' => $this->otherUser->id,
        ]);
    }
}
