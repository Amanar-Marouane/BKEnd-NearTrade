<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'category' => $this->category->category,
            'category_id' => $this->category->id,
            'status' => $this->status,
            'price' => $this->price,
            'location' => $this->location,
            'user_id' => $this->user_id,
            'images' => $this->images ? array_filter(explode('|', $this->images)) : [],
            'isFaved' => $request->user()?->isFaved($this->resource),
            'favorites_count' => $this->favorites_count,
            'reviews' => ReviewResource::collection($this->reviews),
            'average_rating' => $this->averageRating(),
        ];
    }
}
