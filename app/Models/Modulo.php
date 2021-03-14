<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class Modulo extends Model
{
    use Filterable;

    protected $primaryKey = 'modulo_id';
    protected $fillable = ['modulo_id', 'activo', 'icon',
        'link', 'title', 'order',
        'group', 'home', 'parent_id', 'is_mobile', 'codigo'];
}
