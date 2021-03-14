<?php

namespace App\Http\Resources\Setup;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class PersonaDocumento extends JsonResource
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
            'num_doc' => $this->num_doc,
            'img_url' => $this->img_url,
            'persona_id' => $this->persona_id,
            'tipo_documento_id' => $this->tipo_documento_id,
            'tipo_documento_siglas' => !is_null($this->tipoDocumento) ? $this->tipoDocumento->siglas : '',
            'tipo_documento_nombre' => !is_null($this->tipoDocumento) ? $this->tipoDocumento->nombre : '',
            'img_default_url' => env('APP_URL').'/storage/utils/empty.jpeg',
        ];
    }
}
