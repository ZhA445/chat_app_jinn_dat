<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $conversation_id;

    public $message;
    public $user_id;
    public function __construct($conversation_id,$message,$user_id)
    {
        $this->conversation_id = $conversation_id;
        $this->message = $message;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.$this->user_id,),
        ];
    }

    public function broadcastWith()
    {
        return [
            'conversation_id' => $this->conversation_id,
            'message_id' => $this->message->id,
            'user_id' => $this->user_id,
        ];
    }
}
