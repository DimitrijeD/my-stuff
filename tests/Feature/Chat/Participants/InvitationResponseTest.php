<?php

namespace Tests\Feature\Chat\Participants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Chat\ParticipantPivot;
use Illuminate\Support\Facades\Event;
use App\Events\ChatEvents\ParticipantEvents\ParticipantInvitationResponseEvent;
use Tests\Feature\Chat\GroupBuilderTrait;

class InvitationResponseTest extends TestCase
{
    use RefreshDatabase, GroupBuilderTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeGroup(); 

        $this->user = User::factory()->create();
        ParticipantPivot::factory()->create([
            'user_id' => $this->user->id, 
            'group_id' => $this->group->id,
            'accepted' => false,
        ]);

        $this->withHeader( 'Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}" );

        $this->endpoint = '/api/chat/group/invitation-response';
    }

    public function test_accept_invitation_updates_pivot()
    {
        $this->form = [
            'group_id' => $this->group->id,
            'responseToInvitation' => true,
        ];

        $this->post($this->endpoint, $this->form);
        
        $this->assertDatabaseHas('group_participants', [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id, 
            'accepted' => true,
        ]);
    }

    public function test_decline_invitation_deletes_pivot()
    {
        $this->form = [
            'group_id' => $this->group->id,
            'responseToInvitation' => false,
        ];

        $this->post($this->endpoint, $this->form);
        
        $this->assertDatabaseMissing('group_participants', [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id, 
        ]);
    }

    public function test_accept_dispatches_event()
    {
        Event::fake();

        $this->form = [
            'group_id' => $this->group->id,
            'responseToInvitation' => true,
        ];

        $this->post($this->endpoint, $this->form);

        Event::assertDispatched(ParticipantInvitationResponseEvent::class, function ($e) {
            $expected = [
                "accepted" => true,
                'group_id' => $this->group->id,
                'user_id' => $this->user->id, 
            ];

            return $e->data == $expected;
        });
    }

    public function test_decline_dispatches_event()
    {
        Event::fake();

        $this->form = [
            'group_id' => $this->group->id,
            'responseToInvitation' => false,
        ];

        $this->post($this->endpoint, $this->form);

        Event::assertDispatched(ParticipantInvitationResponseEvent::class, function ($e) {
            $expected = [
                "accepted" => false,
                'group_id' => $this->group->id,
                'user_id' => $this->user->id, 
            ];

            return $e->data == $expected;
        });
    }
}
