<?php

namespace Tests\Feature\Chat\Participants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Response\ApiResponse;
use App\Models\Chat\ChatGroup;
use Tests\Feature\Chat\GroupBuilderTrait;

/**
 * As long as ChatRole::CREATOR is the only one which can change group type, these tests are valid
 */
class ChangeGroupTypeTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup(); 
        
        $this->data = [
            'model_type' => ChatGroup::TYPE_PUBLIC_OPEN,
            'group_id' => $this->group->id,
        ];

        $this->endpoint = '/api/chat/group/change-group-type';
    }

    public function test_creator_changes_type_successfully()
    {
        $response = $this->post($this->endpoint, $this->data);

        $response->assertJson(ApiResponse::success([
            'messages' => [[ __('chat.type.success') ]],
        ]));

        $this->assertDatabaseHas('chat_groups', [
            'id' => $this->group->id,
            'model_type' => $this->data['model_type']
        ]);
    }
    
}
