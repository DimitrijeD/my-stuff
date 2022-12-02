<?php

namespace Tests\Feature\Chat\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Chat\ChatGroup;
use Illuminate\Support\Str;

class CreateChatGroupTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $chatParticipant = User::factory()->create();

        $this->withHeader( 'Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}" );

        $this->newGroup = [
            'name' => 'some1111111111111', // for purpose of finding group use some unique name..
            'model_type' => ChatGroup::TYPE_PUBLIC_OPEN,
            'users_ids' => [$chatParticipant->id],
        ];

        $this->storeGroupEndpoint = '/api/chat/group/store';
    }

    public function test_create_group_successfully()
    {
        $response = $this->post($this->storeGroupEndpoint, $this->newGroup);

        $response->assertStatus(201);
    }

    public function test_create_group_returns_group_data_with_participants()
    {
        $response = $this->post($this->storeGroupEndpoint, $this->newGroup);

        $newlyCreatedGroup = ChatGroup::where([
            'name' => $this->newGroup['name'],
            'model_type' => $this->newGroup['model_type'],
        ])->first();

        // fetch with participants by relationship as well
        $newlyCreatedGroup->participants;

        $response->assertJson($newlyCreatedGroup->jsonSerialize());
    }
}