<?php

namespace App\Events\ChatEvents\ParticipantEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAddedToGroupEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data; 
    }

    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->data['group_id']);
    }

    public function broadcastAs()
    {
        return 'participant.added';
    }
}
