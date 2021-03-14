<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolModulo extends Model
{
    // protected $primaryKey = 'id';
    // protected $primaryKey = array('modulo_id', 'rol_id');
    protected $fillable = ['modulo_id', 'rol_id', 'activo'];

}
