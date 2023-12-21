<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BanUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($user)
    {
        $this->users = $user;
    }

    // You should specify the name of the channel created in Pusher.
    public function broadcastOn()
    {
        return new Channel('ban-channel');
    }

    public function broadcastAs()
    {
        return 'ban-received';
    }
}

