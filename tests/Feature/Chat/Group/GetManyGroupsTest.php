<?php

namespace Tests\Feature\Chat\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ChatGroupClusterSeeder;

use App\Models\User;
use App\Models\ChatGroup;
use App\Models\ParticipantPivot;

class GetManyGroupsTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $chatGroupSeeder = resolve(ChatGroupClusterSeeder::class);
        $this->groups = [];

        for($i = 0; $i < 3; $i++){
            $this->groups[] = $chatGroupSeeder->run();
        }
        
        if(!$this->user = User::where(['email' => 'qwe@qwe'])->first())
            $this->markTestIncomplete("Cannot finish this test because target user doesn't exist");

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->user->createToken('app')->plainTextToken}"
        ]);

        $this->getManyGroupsEndpoint = "/api/chat/user/groups";
    }

    public function tests_exact_get_number_of_groups_user_belongs_to()
    {
        $response = $this->get($this->getManyGroupsEndpoint);

        $response->assertJsonCount(count($this->groups));
    }

    public function test_returns_participants_with_pivots()
    {
        $response = $this->get($this->getManyGroupsEndpoint);

        $response->assertJsonStructure([
            [
                'id', 
                'name', 
                'created_at', 
                'updated_at', 
                'model_type',
                'participants' => [
                    [
                        'id',
                        'first_name',
                        'last_name',
                        'image',
                        'thumbnail',
                        'created_at', 
                        'updated_at',
                        'pivot' => [
                            'group_id',
                            'user_id',
                            'last_message_seen_id',
                            'participant_role',
                            'updated_at',
                        ],
                    ],
                ],
            ]
        ]);
    }

    public function test_accesible_by_auth_user()
    {
        $response = $this->get($this->getManyGroupsEndpoint);

        $response->assertStatus(200);
    }

    public function test_not_accesible_by_guest()
    {
        $this->withHeader('Authorization', "Bearer ");

        $response = $this->get($this->getManyGroupsEndpoint);

        $response->assertStatus(401);
    }
}