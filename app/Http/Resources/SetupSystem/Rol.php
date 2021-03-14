<?php

namespace App\Http\Resources\SetupSystem;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class Rol extends JsonResource
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
            'rol_id' => $this->rol_id,
            'nombre' => $this->nombre,
            'activo' => $this->activo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
