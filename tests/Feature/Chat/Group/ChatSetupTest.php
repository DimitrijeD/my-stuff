<?php

namespace Tests\Feature\Chat\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Chat\GroupBuilderTrait;

class ChatSetupTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->makeGroup(); 

        $this->endpoint = "api/chat/init";
    }

    public function test_getting_all_required_data_for_chat_feature_to_function()
    {
        $response = $this->get($this->endpoint);

        $response->assertJsonStructure([
            'chat_rules', 'groups'
        ]);

    }
}
