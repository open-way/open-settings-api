<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $primaryKey = 'ubigeo_id';

    protected $fillable = [
        'ubigeo_id',
        'nombre',
        'parent_id',

        'created_at',
        'updated_at'
    ];
}
