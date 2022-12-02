<?php

namespace Tests\Feature\Chat\Participants\ChangeGroupName;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;

class ChangeGroupNameTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup();

        $this->endpoint = '/api/chat/group/change-group-name';
    }

    public function test_creator_can_change_public_closed_group_name()
    {
        $response = $this->post($this->endpoint, [
            'new_name' => "New Group Name",
            'group_id' => $this->group->id,
        ]);

        $response->assertJson([
            'messages' => [[ __('chat.name.updated') ]],
            "response_type" => "success"
        ]);
    }
}
