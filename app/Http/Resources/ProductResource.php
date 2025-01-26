<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'name' => $this->translations->first()->name ?? $this->name, // Use the translated name or fallback
            'company' => new CompanyResource($this->whenLoaded('company')), // Include company if loaded
            'price' => $this->price,
            'bottle_count'=>$this->bottle_count,
            'description' => $this->translations->first()->description ?? $this->description, // Use the translated name or fallback
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
