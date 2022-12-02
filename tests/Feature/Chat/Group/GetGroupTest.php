<?php

namespace Tests\Feature\Chat\Group;

use App\Models\Chat\ChatGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Tests\Feature\Chat\GroupBuilderTrait;

class GetGroupTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup(); 

        $this->endpoint = "/api/chat/group/{$this->group->id}";
    }

    public function test_gets_group()
    {
        $response = $this->get($this->endpoint);

        $response->assertJson(ChatGroup::where('id', $this->group->id)
            ->with(['participants', 'lastMessage', 'latestMessages'])
            ->first()
            ->jsonSerialize()
        );
    }

    public function test_group_doesnt_exist()
    {
        $response = $this->get("/api/chat/group/34789");

        $response->assertJson([ 
            "messages" => [[ __('model.groupNotFound') ]],
            "response_type" => "error"
        ]);
    }
}
