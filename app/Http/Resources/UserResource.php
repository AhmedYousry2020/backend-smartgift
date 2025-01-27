<?php

namespace App\Http\Resources;

use App\Models\FormGenerateField;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'phone'              => $this->phone,
            'first_name'         => $this->first_name,
            'last_name'          => $this->last_name,
            'address'            => $this->address,
            'status'             => $this->status,
            'image'              => asset('storage/' . $this->image),
            'verify'             => filled($this->phone_verified_at),
            'otp'                => (config('app.enable_otp') && $this->otp)?$this->otp: '',
            'access_token'       => $this->token,
            // 'user_settings'      => SettingResource::collection($this->settings),
        ];
    }
}
