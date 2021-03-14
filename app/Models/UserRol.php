<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRol extends Model
{
    // protected $primaryKey = 'id';
    // protected $primaryKey = ['user_id', 'rol_id'];
    protected $fillable = ['user_id', 'rol_id', 'activo'];
    // public $incrementing = false;
}
