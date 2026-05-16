<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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

            'total_amount' => (float) $this->total_amount,

            'status' => $this->status,

            'delivery_address' => $this->delivery_address,

            'phone' => $this->phone,

            'payment_method' => $this->payment_method,

            'notes' => $this->notes,

            'created_at' => $this->created_at,

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
    
}
