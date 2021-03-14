<?php
/**
 * Created by PhpStorm.
 * User: UPN
 * Date: 21/05/2019
 * Time: 22:55
 */

namespace App\Http\Data;


use Illuminate\Support\Facades\DB;

class ModuloData
{

    public static function getModulosParentsAll() {
        $modulos = DB::table('modulos')
            ->select('modulos.modulo_id', 'modulos.activo',
                'modulos.icon','modulos.link',
                'modulos.title','modulos.order',
                'modulos.is_mobile',
                'modulos.group','modulos.home','modulos.parent_id','modulos.codigo')
            ->whereNull('modulos.parent_id')
            ->orderBy('order')
            ->distinct()
            ->get();
        return $modulos;
    }

    public static function getModulosChildrenAll($modulo_id) {
        $modulos = DB::table('modulos')
            ->select('modulos.modulo_id', 'modulos.activo',
                'modulos.icon','modulos.link',
                'modulos.title','modulos.order',
                'modulos.is_mobile',
                'modulos.group','modulos.home','modulos.parent_id','modulos.codigo')
            ->where('parent_id', '=', $modulo_id)
            ->orderBy('order')
            ->distinct()
            ->get();
        return $modulos;
    }

    public static function getModulosParents($user_id, $modulo_id) {
        $modulos = DB::table('modulos')
            ->select('modulos.modulo_id', 'modulos.activo',
                'modulos.icon','modulos.link',
                'modulos.title','modulos.order',
                'modulos.group','modulos.home','modulos.parent_id','modulos.codigo')
            ->join('rol_modulos','modulos.modulo_id', '=', 'rol_modulos.modulo_id')
            ->join('user_rols', 'rol_modulos.rol_id', '=', 'user_rols.rol_id')
            ->where('user_rols.user_id', '=',$user_id)
            ->where('modulos.is_mobile', '=',false)
            ->where('modulos.parent_id', '=',$modulo_id)
            ->orderBy('order')
            ->distinct()
            ->get();
        return $modulos;
    }

    // public static 

    public static function getModulosChildren($modulo_id, $user_id) {
        $modulos = DB::table('modulos')
            ->select('modulos.modulo_id', 'modulos.activo',
                'modulos.icon','modulos.link',
                'modulos.title','modulos.order',
                'modulos.group','modulos.home','modulos.parent_id','modulos.codigo')
            ->join('rol_modulos','modulos.modulo_id', '=', 'rol_modulos.modulo_id')
            ->join('user_rols', 'rol_modulos.rol_id', '=', 'user_rols.rol_id')
            ->where('user_rols.user_id', '=',$user_id)
            ->where('modulos.is_mobile', '=',false)
            ->where('parent_id', '=', $modulo_id)
            ->orderBy('order')
            ->distinct()
            ->get();
        return $modulos;
    }

    public static function getById($modulo_id) {
        $pastor = DB::table('modulos')
            ->where('modulo_id','=', $modulo_id)
            ->first();
        return $pastor;
    }

    public static function getByCodigo($modulo_codigo) {
        $pastor = DB::table('modulos')
            ->where('codigo','=', $modulo_codigo)
            ->first();
        return $pastor;
    }
}
