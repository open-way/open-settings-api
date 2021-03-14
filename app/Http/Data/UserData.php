<?php
/**
 * Created by PhpStorm.
 * User: UPN
 * Date: 21/05/2019
 * Time: 22:23
 */

namespace App\Http\Data;


use App\Models\UserDistritoMisionero;
use App\Models\UserIglesia;
use App\Models\UserMisionAsociacion;
use App\Models\UserUnion;
use Illuminate\Support\Facades\DB;

class UserData
{
    public static function getRols($user_id)
    {
        $rols = DB::table('rols')
            ->select('rols.rol_id', 'rols.activo', 'rols.nombre')
            ->selectRaw( '(select count(1) from user_rols ur
                                    where ur.user_id='.$user_id.'
                                    and ur.rol_id=rols.rol_id) as user_rol_existe'
            )
            ->where('rols.activo', '=', true)
            ->orderBy('rols.rol_id')
            ->get();

        return $rols;
    }
}
