<?php

namespace App\Events;

use App\Http\Controllers\ChatIdsController;
use App\Models\ChatIds;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $chat_id = ChatIdsController::findOrMake($this->message->sender_id, $this->message->receiver_id);
        return new Channel('chat.' . $chat_id);
    }

    public function broadcastAs()
    {
        return 'chatEvent';
    }
}
