<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'order_type' => $this->order_type,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'order_details' => OrderDetailResource::collection($this->orderDetails),
            'order_categories' => OrderCategoryResource::collection($this->orderCategories),
        ];
    }
}
