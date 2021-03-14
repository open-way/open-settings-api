<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaVirtual extends Model
{
    protected $primaryKey = 'persona_virtual_id';

    protected $fillable = [
        'persona_virtual_id',
        'direccion',
        'activo',
        'persona_id',
        'tipo_virtual_id'
    ];

    public function tipoVirtual()
    {
        return $this->belongsTo('App\Models\TipoVirtual', 'tipo_virtual_id');
    }
}
