<?php

namespace App\Events;

use App\Models\Chats;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Chats $chat;

    public function __construct(Chats $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn(): Channel
    {
        // User↔Admin chats use private channel per order
        if ($this->chat->order_id) {
            return new Channel("order.{$this->chat->order_id}");
        }
        // Admin↔Delivery channel
        return new Channel("delivery");
    }

    public function broadcastWith(): array
    {
        return [
            'id'            => $this->chat->id,
            'sender_role'   => $this->chat->sender_role,
            'receiver_role' => $this->chat->receiver_role,
            'message'       => $this->chat->message,
            'timestamp'     => $this->chat->created_at->toDateTimeString(),
        ];
    }
}
