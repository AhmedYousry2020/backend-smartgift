<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MosqueResource extends JsonResource
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
            'is_high_need' => $this->is_high_need,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'city'=>$this->city->name,
            'lat'=>$this->lat,
            'lng'=>$this->lng,
            'address'=>$this->address,
            'image'=>asset('storage/' . $this->image),
            // Add any other fields you'd like to expose
        ];
    }
}
