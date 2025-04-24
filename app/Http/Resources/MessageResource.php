<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'sender' => $this->sender->name,
            'timestamp' => $this->created_at->format('Y-m-d H:i'),
            'type' => $this->type,
            'product' => new ProductResource($this->product),
            'status' => $this->status,
            'offer' => $this->offer,
        ];
    }
}
