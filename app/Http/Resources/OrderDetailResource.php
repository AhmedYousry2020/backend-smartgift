<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'mosque' => $this->mosque ? [
                'id' => $this->mosque->id,
                'name' => $this->mosque->name,
            ] : null,
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
            ],
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
        ];
    }
}
