<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoVirtualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Correo electrónico',
            'siglas' => 'E-Mail',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Página Web',
            'siglas' => 'Url',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Mensajería instantanea',
            'siglas' => 'IM',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Twitter',
            'siglas' => 'Twitter',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Facebook',
            'siglas' => 'Facebook',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'YouTube',
            'siglas' => 'YouTube',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Instagram',
            'siglas' => 'Instagram',
        ]);
        DB::table('tipo_virtuals')->insert([
            'nombre' => 'Google+',
            'siglas' => 'Google+',
        ]);
    }
}
