<?php

namespace Tests\Feature\Chat\GroupRules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SetRoleRulesInCacheTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        
        $this->withHeader( 'Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}" );
    }

    public function test_set_all_rules()
    {
        $response = $this->get('/api/chat/role-rules/set');

        $response->assertJson([
            'success' => __("Role rules successfully cached.")
        ]);
    }

    public function test_get_all_rules()
    {
        $response = $this->get('/api/chat/role-rules/get');

        $response->assertStatus(200);
    }
}
