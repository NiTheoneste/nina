<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'user' => UserResource::make($this->whenLoaded('user')),
            'orderItems' => OrderItemCollection::make($this->whenLoaded('orderItems')),
            'products' => ProductCollection::make($this->whenLoaded('products')),
        ];
    }
}
