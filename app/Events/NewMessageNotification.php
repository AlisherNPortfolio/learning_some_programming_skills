<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->to); // <== PrivateChannel-da yuboramiz
    }

    /**
     * Agar bu metod e'lon qilinmagan bo'lsa,
     * Echo.private(...).listen('.my-chat') o'rniga
     * Echo.private(...).listen('NewMessageNotification') yoziladi
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'my-chat';
    }
}
