<?php

namespace App\Events;

use App\Models\OrderMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(OrderMessage $message)
    {
        $this->message = $message->load('user');
    }

    public function broadcastOn(): Channel
    {
        // Canal público por orden (si quieres privado usa PrivateChannel)
        return new Channel('order.' . $this->message->order_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'order_id' => $this->message->order_id,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->message,
            'user' => [
                'id' => $this->message->user->id ?? null,
                'name' => $this->message->user->name ?? 'Sistema',
            ],
            'created_at' => $this->message->created_at->toISOString(),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'NewOrderMessage';
    }
}