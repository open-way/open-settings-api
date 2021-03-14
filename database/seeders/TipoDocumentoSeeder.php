<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 0,
            'nombre' => 'Otros',
            'siglas' => 'Otro',
            'vigencia' => null,
        ]);
            
        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 1,
            'nombre' => 'Documento Nacional de Identidad',
            'siglas' => 'DNI',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 4,
            'nombre' => 'Carnet de Extranjería',
            'siglas' => 'CarEx',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 6,
            'nombre' => 'Registro Único de Contribuyente',
            'siglas' => 'RUC',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 7,
            'nombre' => 'Pasaporte',
            'siglas' => 'Pass',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 11,
            'nombre' => 'Partida de Nacimiento',
            'siglas' => 'Part',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 96,
            'nombre' => 'Número de Resolución',
            'siglas' => 'NR',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 97,
            'nombre' => 'Código del Seguro Nacional de Pensiones',
            'siglas' => 'SNP',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 99,
            'nombre' => 'Documento Interno del Sistema',
            'siglas' => 'DOI',
            'vigencia' => null,
        ]);

        DB::table('tipo_documentos')->insert([
            'tipo_documento_id' => 98,
            'nombre' => 'Código Único de Identificación del Sistema Privado de Pensiones',
            'siglas' => 'CUSPP',
            'vigencia' => null,
        ]);
    }
}
