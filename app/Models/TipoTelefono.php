<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTelefono extends Model
{
    protected $primaryKey = 'tipo_telefono_id';
    protected $fillable = ['tipo_telefono_id', 'nombre'];
}
