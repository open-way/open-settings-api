<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ModuloFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];
    // 'modulo_id', 'activo', 'icon',
    //     'link', 'title', 'order',
    //     'group', 'home', 'parent_id', 'is_mobile', 'codigo'

    public function stripAccents($str)
    {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }

    // ?text_search=hola
    public function textSearch($text_search)
    {
        $ntext_search = str_replace(' ', '', $this->stripAccents($text_search));
        $utext_search = strtoupper($ntext_search);
        return $this->whereRaw("fn_remove_accents(replace(concat(upper(coalesce(title, '')),upper(coalesce(codigo, '')),upper(coalesce(link, ''))), ' ', '')) like upper(?)", ['%'.$utext_search.'%']);
    }
}
