<?php

namespace App\Http\Resources\Setup;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class PersonaTelefono extends JsonResource
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
            'persona_telefono_id' => $this->persona_telefono_id,
            'num_telefono' => $this->num_telefono,
            'es_privado' => $this->es_privado,
            'activo' => $this->activo,
            'persona_id' => $this->persona_id,
            'tipo_telefono_id' => $this->tipo_telefono_id,
            'tipo_telefono_nombre' => !is_null($this->tipoTelefono) ? $this->tipoTelefono->nombre : '',
        ];
    }
}
