<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Http\Resources\ChatIdsResource;
use App\Http\Resources\MessageResource;
use App\Models\{ChatIds, Chat, User};
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use HttpsResponse;

    public function sendMessage(Request $request)
    {
        $messageToInsert = Chat::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $message = (object) [
            'id' => $messageToInsert->id,
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'body' => $request->message,
            'sender' => $request->user()->name,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ];

        broadcast(new ChatEvent($message));

        return $this->success('Message Sent', $request->message);
    }

    public function index(Request $request, $id)
    {
        $chat = ChatIds::find($id);
        $otherUserId = $chat->user1 !== $request->user()->id ? $chat->user1 : $chat->user2;
        $otherUser = User::find($otherUserId);

        $messages = Chat::where(function ($query) use ($chat) {
            $query->where('sender_id', $chat->user1)
                ->where('receiver_id', $chat->user2);
        })->orWhere(function ($query) use ($chat) {
            $query->where('sender_id', $chat->user2)
                ->where('receiver_id', $chat->user1);
        })->get();

        return $this->success($otherUser->name, MessageResource::collection($messages));
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $chats = ChatIds::where('user1', $user->id)
            ->orWhere('user2', $user->id)
            ->get();

        return $this->success(null, ChatIdsResource::collection($chats));
    }
}
