<?php

namespace App\Events;

use Auth;
use App\Notification;
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


    public function __construct($message,$id,$link= null,$push=false)
    {
        $this->message = $message;
        $this->id = $id;
        $this->link=$link;
        $this->push=$push;
        $notification=new Notification;
        $notification->user_id=$id;
        $notification->message = $message;
        $notification->link = $link;
        $notification->status = "unread";
        $notification->save();
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
