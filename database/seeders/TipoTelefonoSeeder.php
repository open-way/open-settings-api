<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoTelefonoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Trabajo',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Fax trabajo',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Particular',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Fax particular',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Móvil',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Radio',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Busca Personas',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Telefóno fiscal',
        ]);
        DB::table('tipo_telefonos')->insert([
            'nombre' => 'Casa',
        ]);
    }
}
