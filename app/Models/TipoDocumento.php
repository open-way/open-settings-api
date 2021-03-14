<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $primaryKey = 'tipo_documento_id';
    protected $fillable = ['tipo_documento_id', 'nombre', 'siglas', 'vigencia'];
}
