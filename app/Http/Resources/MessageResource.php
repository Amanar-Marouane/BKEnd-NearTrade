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
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->user()->id === $this->sender_id ? $this->receiver_id : $this->sender_id,
            'sender' => $request->user()->name,
            'timestamp' => $this->created_at,
            'isMe' => $request->user()->id === $this->sender_id ? true : false,
        ];
    }
}
