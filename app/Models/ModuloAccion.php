<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuloAccion extends Model
{
    protected $primaryKey = 'modulo_accion_id';
    protected $fillable = ['modulo_accion_id', 'nombre', 'llave', 'modulo_id'];
}
