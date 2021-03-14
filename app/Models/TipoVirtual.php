<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVirtual extends Model
{
    protected $primaryKey = 'tipo_virtual_id';
    protected $fillable = ['tipo_virtual_id', 'nombre', 'siglas'];
}
