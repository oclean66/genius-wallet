<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'country'        => $this->country,
            'city'           => $this->city,
            'zip'            => $this->zip,
            'address'        => $this->address,
            'profile_photo'  => getPhoto($this->photo),
            'email_verified' => $this->email_verified == 1 ? true:false,
            'kyc_status'     => $this->kyc_status,
            'two_fa_status'  => $this->two_fa_status,
            'two_fa'         => $this->two_fa,
            'two_fa_code'    => $this->two_fa_code,
            'status'         => $this->status,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
