<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type, // 'video' or 'images'
            'translations' => $this->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'title' => $translation->title,
                    'description' => $translation->description,
                ];
            }),
            'media' => $this->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'media_path' => asset('storage/' . $media->media_path),
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
