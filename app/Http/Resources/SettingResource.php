<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'terms_and_conditions' => $this->translations->first()->terms_and_conditions,
            'privacy_policy' => $this->translations->first()->privacy_policy,

        ];
    }
}
