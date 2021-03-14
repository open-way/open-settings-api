<?php

namespace App\Http\Resources\Setup;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class PersonaDireccion extends JsonResource
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
            'persona_direccion_id' => $this->persona_direccion_id,
            'referencia' => $this->referencia,
            'map_latitud' => $this->map_latitud,
            'map_longitud' => $this->map_longitud,
            'activo' => $this->activo,
            'persona_id' => $this->persona_id,
            'ubigeo_depto_id' => strlen($this->ubigeo_id)===5?(int)substr($this->ubigeo_id,0,1):(int)substr($this->ubigeo_id,0,2),
            'ubigeo_pvcia_id' => strlen($this->ubigeo_id)===5?(int)substr($this->ubigeo_id,0,3):(int)substr($this->ubigeo_id,0,4),
            'ubigeo_ditto_id' => $this->ubigeo_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
