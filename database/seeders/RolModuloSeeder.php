<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modulos = DB::table('modulos')->get();

        foreach ($modulos as $modulo) {
            DB::table('rol_modulos')->insert([
                'activo' => true,
                'modulo_id' => $modulo->modulo_id,
                'rol_id' => 1,
            ]);
        }
    }
}
