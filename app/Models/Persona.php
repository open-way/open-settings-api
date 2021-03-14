<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class Persona extends Model
{
    use Filterable;

    protected $primaryKey = 'persona_id';

    protected $fillable = [
        'persona_id',
        'nombres',
        'ap_paterno',
        'ap_materno',
        'estado_civil',
        'fecha_nac',
        'sexo',
        'activo',
        'ubigeo_id',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'persona_id');
    }

    public function personaDocumentos()
    {
        return $this->hasMany('App\Models\PersonaDocumento', 'persona_id', 'persona_id');
    }

        // public function fotos()
    // {
    //     return $this->hasMany('App\Models\Jose\MsmAlbumFoto', 'id_album', 'id_album')->orderBy('created_at');
    // }

    // public function countfotos()
    // {
    //     return $this->hasMany('App\Models\Jose\MsmAlbumFoto', 'id_album', 'id_album')->count();
    // }
}
