<?php

namespace App\Http\Resources\Setup;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class Persona extends JsonResource
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
            'persona_id' => $this->persona_id,
            'nombres' => $this->nombres,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno,
            'estado_civil' => $this->estado_civil,
            'sexo' => $this->sexo,
            'activo' => $this->activo,
            'fecha_nac' => $this->fecha_nac,
            'ubigeo_depto_id' => strlen($this->ubigeo_id)===5?(int)substr($this->ubigeo_id,0,1):(int)substr($this->ubigeo_id,0,2),
            'ubigeo_pvcia_id' => strlen($this->ubigeo_id)===5?(int)substr($this->ubigeo_id,0,3):(int)substr($this->ubigeo_id,0,4),
            'ubigeo_ditto_id' => $this->ubigeo_id,

            'user_name' => !is_null($this->user) ? $this->user->email : '',
            'img_url' => !is_null($this->user) ? $this->user->img_url : '',
            'img_default_url' => env('APP_URL').'/storage/utils/empty.jpeg',

            'fecha_parse' => $this->created_at ? (new Date($this->created_at))->format('j \d\e F, \a \l\a\s H:i') : 'None',
            'fecha_nac_parse' => $this->fecha_nac ? (new Date($this->fecha_nac))->format('j \d\e F, \d\e\l Y') : 'None',
            'fullname' => strtoupper($this->ap_paterno.' '.$this->ap_materno).', '.ucwords(strtolower($this->nombres)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
