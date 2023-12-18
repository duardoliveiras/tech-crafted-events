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

class NotificationReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $message;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($request)
    {
        $this->message = 'You Recevied a new notification' . $request;
    }

    // You should specify the name of the channel created in Pusher.
    public function broadcastOn()
    {
        return new Channel('notification-channel');
    }

    public function broadcastAs()
    {
        return 'notification-received';
    }
}

