<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaTelefono extends Model
{
    protected $primaryKey = 'persona_telefono_id';

    protected $fillable = [
        'persona_telefono_id',
        'num_telefono',
        'es_privado',
        'activo',
        'persona_id',
        'tipo_telefono_id'
    ];

    public function tipoTelefono()
    {
        return $this->belongsTo('App\Models\TipoTelefono', 'tipo_telefono_id');
    }
}
