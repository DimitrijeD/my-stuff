<?php

namespace Tests\Feature\Chat\Participants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Response\ApiResponse;
use Database\Seeders\ChatGroupClusterSeeder;
use App\Models\ChatGroup;
use App\Models\ChatRole;

/**
 * As long as ChatRole::CREATOR is the only one which can change group type, these tests are valid
 */
class ChangeGroupTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatGroupSeeder = (resolve(ChatGroupClusterSeeder::class));
        
        $this->chatGroupSeeder->massSetter([
            'model_type' => $this->currentGroupModelType = ChatGroup::TYPE_PUBLIC_CLOSED,
        ]);
        $this->allChatData = $this->chatGroupSeeder->run();
        
        $this->data = [
            'model_type' => $this->targetGroupModelType = ChatGroup::TYPE_PUBLIC_OPEN,
            'group_id' => $this->allChatData['group']->id,
        ];

        $this->endpoint = '/api/chat/group/change-group-type';
    }

    public function test_creator_changes_type_successfully()
    {
        $this->withHeader('Authorization', "Bearer {$this->allChatData['group_creator']->createToken('app')->plainTextToken}");

        $response = $this->post($this->endpoint, $this->data);

        $response->assertJson(ApiResponse::success([
            'messages' => [[ __('chat.type.success') ]],
        ]));

        $this->assertDatabaseHas('chat_groups', [
            'id' => $this->allChatData['group']->id,
            'model_type' => $this->targetGroupModelType
        ]);
    }

    public function test_non_creator_role_cannot_change_type()
    {
        if($this->targetGroupModelType == $this->currentGroupModelType)
            $this->markTestSkipped('Current group model_type and type we are trying to update must be different to make this test valid otherwise test will be false positive');

        $nonCreator = $this->allChatData['users']->where('participant_role', '!=', ChatRole::CREATOR)->first();

        $this->withHeader('Authorization', "Bearer {$nonCreator->createToken('app')->plainTextToken}");

        $response = $this->post($this->endpoint, $this->data);

        $this->assertDatabaseMissing('chat_groups', [
            'id' => $this->allChatData['group']->id,
            'model_type' => $this->targetGroupModelType
        ]);
    }
}
