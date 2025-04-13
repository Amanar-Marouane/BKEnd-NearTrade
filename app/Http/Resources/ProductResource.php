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
            'category' => $this->category?->category,
            'status' => $this->status,
            'price' => $this->price,
            'location' => $this->location,
            'images' => $this->images ? array_filter(explode('|', $this->images)) : [],
            'canDelete' => Auth::user()->can('delete', $this->resource),
        ];
    }
}
