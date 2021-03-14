<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class PersonaDocumento extends Model
{
    use Filterable;

    protected $primaryKey = 'num_doc';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'num_doc',
        'img_url',
        'persona_id',
        'tipo_documento_id'
    ];
    
    public function tipoDocumento()
    {
        return $this->belongsTo('App\Models\TipoDocumento', 'tipo_documento_id');
    }
}
