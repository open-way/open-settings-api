<?php

namespace App\Http\Resources\Setup;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password' => $this->password,
            'active' => $this->active,
            'is_superuser' => $this->is_superuser,
            'img_url' => $this->img_url,
            'img_default_url' => env('APP_URL').'/storage/utils/empty.jpeg',

            'remember_token' => $this->remember_token,
        ];
    }
}
