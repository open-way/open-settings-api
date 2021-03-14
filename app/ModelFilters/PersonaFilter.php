<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PersonaFilter extends ModelFilter
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
        return $this->where(function($q) use ($utext_search) {
                return $q->whereHas('personaDocumentos', function ($query) use ($utext_search) {
                    $query->whereRaw("fn_remove_accents(replace(upper(num_doc), ' ', '')) like upper(?)", '%'.$utext_search.'%');
                    // $query->whereIn("num_doc", [$utext_search]);
                    // $query->where("num_doc", $utext_search);
                })->orWhereRaw("fn_remove_accents(replace(concat(upper(coalesce(nombres,'')),upper(coalesce(ap_paterno,'')),upper(coalesce(ap_materno,''))), ' ', '')) like upper(?)", ['%'.$utext_search.'%']);
            });
    }
    //  public function textSearch($text_search)
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
