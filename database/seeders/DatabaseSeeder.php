<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
// use App\UbigeoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Model::unguard();
        // $this->call(PersonaSeeder::class);
        $this->call(UbigeoSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(TipoTelefonoSeeder::class);
        $this->call(TipoVirtualSeeder::class);
        $this->call(ModuloSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(RolModuloSeeder::class);
        Model::reguard();
    }
}
