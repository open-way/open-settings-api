<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolMaccion extends Model
{
    // protected $primaryKey = 'modulo_accion_id';
    protected $fillable = ['modulo_accion_id', 'rol_id', 'activo' ];
}
