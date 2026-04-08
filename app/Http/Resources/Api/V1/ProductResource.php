<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (int) $this->price,
            'description' => $this->description,
            'material' => $this->material,
            'color' => $this->material,
            'dimension' => $this->dimension,
            'stock' => $this->stock,
            'featured' => $this->featured,

            'images' => $this->images->pluck('url'),

            'category' => [
                'id' => $this->category->id,
                'slug' => $this->category->slug,
                'title' => $this->category->title,
            ],
        ];
    }
}
