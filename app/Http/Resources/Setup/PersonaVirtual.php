<?php

namespace App\Http\Resources\Setup;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class PersonaVirtual extends JsonResource
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
            'persona_virtual_id' => $this->persona_virtual_id,
            'direccion' => $this->direccion,
            'activo' => $this->activo,
            'persona_id' => $this->persona_id,
            'tipo_virtual_id' => $this->tipo_virtual_id,
            'tipo_virtual_nombre' => !is_null($this->tipoVirtual) ? $this->tipoVirtual->nombre : '',
            'tipo_virtual_siglas' => !is_null($this->tipoVirtual) ? $this->tipoVirtual->siglas : '',
        ];
    }
}
