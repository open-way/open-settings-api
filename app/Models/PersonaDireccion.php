<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaDireccion extends Model
{
    protected $primaryKey = 'persona_direccion_id';

    protected $fillable = [
        'persona_direccion_id',
        'referencia',
        'map_latitud',
        'map_longitud',
        'activo',
        'persona_id',
        'ubigeo_id',
    ];
}
