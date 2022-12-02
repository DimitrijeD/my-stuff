<?php

namespace Tests\Feature\Chat\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;

class GetInitialGroupsWithLatestMessageTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;
    
    protected function setUp(): void
    {
        parent::setUp();

        for($i = 0; $i < 3; $i++){
            $this->makeGroup(); 
        }

        $this->endpoint = "/api/chat/user/groups";
    }

    public function test_gets_groups_with_latest_message()
    {
        $response = $this->get($this->endpoint);

        $response->assertJson( $this->user->groups->jsonSerialize() );
    }
}
