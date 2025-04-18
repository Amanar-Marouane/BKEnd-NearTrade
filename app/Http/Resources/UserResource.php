<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'role' => $this->role,
            'profile' => 'http://127.0.0.1:8000/' . $this->profile,
            'member_since' => Carbon::parse($this->created_at)->year,
            'lastActiveProducts' => ProductResource::collection($this->lastActiveProducts),
        ];
    }
}
