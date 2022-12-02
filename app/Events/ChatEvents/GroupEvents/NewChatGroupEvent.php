<?php

namespace App\Events\ChatEvents\GroupEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatGroupEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    private $toUsers;

    public function __construct($data, array $toUsers)
    {
        $this->data = $data;

        $this->toUsers = $this->broadcastsToOthers($toUsers);
    }

    private function broadcastsToOthers($toUsers)
    {
        if (($key = array_search(auth()->user()->id, $toUsers)) !== false) unset($toUsers[$key]);
        
        return $toUsers;
    }

    public function broadcastOn()
    {
        $channels = [];

        foreach($this->toUsers as $user_id){
            $channels[] = new PrivateChannel("App.Models.User.{$user_id}"); 
        }

        return $channels;
    }

    public function broadcastAs() {
        return 'group.new';
    }
}
