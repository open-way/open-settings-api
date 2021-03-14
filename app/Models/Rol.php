<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class Rol extends Model
{
    use Filterable;

    protected $primaryKey = 'rol_id';
    protected $fillable = ['rol_id', 'nivel','nombre', 'activo'];
}
