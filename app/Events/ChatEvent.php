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
        Log::info('construct');
        $this->message = $message;
    }

    public function broadcastOn()
    {
        Log::info('broadcastOn');
        return new Channel('chat.' . $this->message->chat_id);
    }

    public function broadcastAs()
    {
        Log::info('broadcastAs');
        return 'chatEvent';
    }
}
