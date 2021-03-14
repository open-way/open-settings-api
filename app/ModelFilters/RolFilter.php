<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class RolFilter extends ModelFilter
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
        return $this->whereRaw("fn_remove_accents(replace(upper(nombre), ' ', '')) like upper(?)", ['%'.$utext_search.'%']);
    }
}
