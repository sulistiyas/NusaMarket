<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'formatted_price' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'stock' => (int) $this->stock,
            'weight' => (int) $this->weight,
            'images' => $this->images ?? [],
            'is_active' => (bool) $this->is_active,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'store_name' => $this->store->name ?? 'NusaMarket Store',
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
