<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Loop through the items to include total price
        $items = $this->items->map(function ($cartItem) {
            return [
                'id' => $cartItem->id,
                'product_name' => $cartItem->product->name,
                'mosque_name' => $cartItem->mosque->name,
                'quantity' => $cartItem->quantity,
                'total_price' => $cartItem->total_price, // Total price for the item (price * quantity)
            ];
        });

        return [
            'success' => true,
            'message' => 'Cart Details',
            'data' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'items' => $items,
            ],
        ];


    }
}
