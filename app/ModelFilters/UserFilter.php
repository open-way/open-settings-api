<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    public function stripAccents($str)
    {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }

    // ?text_search=hola
    public function textSearch($text_search)
    {
        $ntext_search = str_replace(' ', '', $this->stripAccents($text_search));
        $utext_search = strtoupper($ntext_search);
        return $this->whereRaw("fn_remove_accents(replace(concat(upper(coalesce(name,'')),upper(coalesce(email,''))), ' ', '')) like upper(?)", ['%'.$utext_search.'%']);
    }

    // public function idAnho($id_anho) {
    //     return $this->where("id_anho", $id_anho);
    // }
    // public function idMes($id_mes) {
    //     return $this->where("id_mes", $id_mes);
    // }
    // public function estadoActual($estado_actual) {
    //     return $this->where("estado_actual", $estado_actual);
    // }
    
    // public function stripAccents($str)
    // {
    //     return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    // }
    // public function textSearch($text_search)
    // {
    //     $ntext_search = str_replace(' ', '', $this->stripAccents($text_search));
    //     $utext_search = strtoupper($ntext_search);
    //     return $this->where(function($q) use ($utext_search)
    //     {
    //         return $q->whereHas('persona', function ($query) use ($utext_search) {
    //             $query->whereRaw("CONVERT(replace(upper(paterno||materno||nombre), ' ', ''), 'US7ASCII') like ?",['%'.$utext_search.'%']);
    //         })->orWhereHas('personanatural', function ($query) use ($utext_search) {
    //             $query->whereRaw("CONVERT(replace(upper(num_documento), ' ', ''), 'US7ASCII') like ?",['%'.$utext_search.'%']);
    //         })->orWhereHas('mes', function ($query) use ($utext_search) {
    //             $query->whereRaw("CONVERT(replace(upper(nombre), ' ', ''), 'US7ASCII') like ?",['%'.$utext_search.'%']);
    //         })->orWhereRaw("(lpad(numero,4,'0') like ? or importe_ayuda like ?)",['%'.$utext_search.'%','%'.$utext_search.'%']);
    //     });
        
    // }
}
