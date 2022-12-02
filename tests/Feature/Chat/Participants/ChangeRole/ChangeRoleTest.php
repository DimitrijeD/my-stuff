<?php

namespace Tests\Feature\Chat\Participants\ChangeRole;

use App\Models\Chat\ChatGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Chat\ChatRole;
use Tests\Feature\Chat\GroupBuilderTrait;

class ChangeRoleTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = 'api/chat/group/change-user-role';
    }

    public function test_creator_can_change_participant_to_moderator_in_public_open()
    {
        $this->makeGroupWith([
            'model_type' => ChatGroup::TYPE_PUBLIC_OPEN,

        ]);

        $response = $this->post($this->endpoint, [
            'target_user_id' => $this->otherUser->id,
            'group_id' => $this->group->id,
            'to_role' => ChatRole::MODERATOR,
        ]);

        $response->assertJson([
            'messages' => [[ __('chat.participants.role.change') ]],
        ]);
    }
}
