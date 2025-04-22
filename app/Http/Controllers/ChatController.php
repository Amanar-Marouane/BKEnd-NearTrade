<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Http\Resources\ChatIdsResource;
use App\Http\Resources\MessageResource;
use App\Models\{ChatIds, Chat, Product, User};
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    use HttpsResponse;

    public function sendMessage(Request $request)
    {
        $chat_id = ChatIdsController::findOrMake($request->user()->id, $request->receiver_id);

        $messageToInsert = Chat::create([
            'chat_id' => $chat_id,
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $message = new MessageResource($messageToInsert);
        broadcast(new ChatEvent($message));

        return $this->success('Message Sent', $message);
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
        })->orderBy('created_at', 'asc')
            ->get();

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

    public function isUserId($id)
    {
        $user = User::find($id);
        if ($user) return $this->success('User does exist', true);
        return $this->success('User does not exist', false);
    }

    public function store(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('Product not found', null, [], 404);
        }

        $chatId = ChatIdsController::findOrMake($request->user()->id, $product->user_id);

        $deal = Chat::create([
            'chat_id'     => $chatId,
            'sender_id'   => $request->user()->id,
            'receiver_id' => $product->user_id,
            'message'     => 'I propose a deal starting with',
            'product_id'  => $id,
            'type'        => 'Offer',
            'status'      => 'Pending',
            'offer'       => $request->offer,
        ]);

        if (!$deal) {
            return $this->error('Failed to create deal', null, [], 500);
        }

        $message = new MessageResource($deal);
        broadcast(new ChatEvent($message));

        return $this->success('Deal has been submitted successfully');
    }
}
