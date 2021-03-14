<?php

namespace App\Http\Resources\SetupSystem;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;
use App\Models\RolMaccion;

class ModuloAccion extends JsonResource
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
            'nombre' => $this->nombre,
            'llave' => $this->llave,
            'modulo_id' => $this->modulo_id,
            'modulo_accion_id' => $this->modulo_accion_id,
            
            'asignado' => !is_null($this->rol_id) ? RolMaccion::where('modulo_accion_id', $this->modulo_accion_id)
                                                    ->where('rol_id', $this->rol_id)
                                                    ->where('activo', true)->exists() : false,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
