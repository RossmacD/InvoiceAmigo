<?php

namespace App\Events;

use Auth;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $id;


    public function __construct($message,$id)
    {
        $this->message = $message;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return new Channel('notifications.'. $this->id);
    }

    public function broadcastAs()
    {
        return 'notification';
    }
}
