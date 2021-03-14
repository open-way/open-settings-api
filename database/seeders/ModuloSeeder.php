<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modulos')->insert([
            'activo' => true,
            'icon' => 'color-palette-outline',
            'link' => '/',
            'title' => 'Root',
            'order' => 1,
            'group' => false,
            'home' => true,
            'parent_id' => null,
            'is_mobile' => false,
            'codigo' => '01000000'
        ]);

            DB::table('modulos')->insert([
                'activo' => true,
                'icon' => 'color-palette-outline',
                'link' => '/',
                'title' => 'Open settings',
                'order' => 1,
                'group' => false,
                'home' => true,
                'parent_id' => 1,
                'is_mobile' => false,
                'codigo' => '01010000'
            ]);

                DB::table('modulos')->insert([
                    'activo' => true,
                    'icon' => 'color-palette-outline',
                    'link' => '/dashboard',
                    'title' => 'Home',
                    'order' => 1,
                    'group' => false,
                    'home' => true,
                    'parent_id' => 2,
                    'is_mobile' => false,
                    'codigo' => '01010100'
                ]);

                DB::table('modulos')->insert([
                    'activo' => true,
                    'icon' => 'settings-2-outline',
                    'link' => '/setup-system',
                    'title' => 'Configuración del sistema',
                    'order' => 2,
                    'group' => false,
                    'home' => false,
                    'parent_id' => 2,
                    'is_mobile' => false,
                    'codigo' => '01010200'
                ]);

                    DB::table('modulos')->insert([
                        'activo' => true,
                        'icon' => 'person-outline',
                        'link' => '/setup-system/usuarios',
                        'title' => 'Usuarios',
                        'order' => 1,
                        'group' => false,
                        'home' => false,
                        'parent_id' => 4,
                        'is_mobile' => false,
                        'codigo' => '01010201'
                    ]);

                    DB::table('modulos')->insert([
                        'activo' => true,
                        'icon' => 'person-outline',
                        'link' => '/setup-system/personas',
                        'title' => 'Personas',
                        'order' => 2,
                        'group' => false,
                        'home' => false,
                        'parent_id' => 4,
                        'is_mobile' => false,
                        'codigo' => '01010202'
                    ]);

                    DB::table('modulos')->insert([
                        'activo' => true,
                        'icon' => 'people-outline',
                        'link' => '/setup-system/roles',
                        'title' => 'Roles',
                        'order' => 3,
                        'group' => false,
                        'home' => false,
                        'parent_id' => 4,
                        'is_mobile' => false,
                        'codigo' => '01010203'
                    ]);

                    DB::table('modulos')->insert([
                        'activo' => true,
                        'icon' => 'layers-outline',
                        'link' => '/setup-system/system-modules',
                        'title' => 'Módulos',
                        'order' => 4,
                        'group' => false,
                        'home' => false,
                        'parent_id' => 4,
                        'is_mobile' => false,
                        'codigo' => '01010204'
                    ]);
    }
}
