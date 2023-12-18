<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
