<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolMaccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_maccions', function (Blueprint $table) {
            $table->boolean('activo')->default(true);
            $table->bigInteger('modulo_accion_id')->unsigned();
            $table->bigInteger('rol_id')->unsigned();
            
            $table->foreign('modulo_accion_id')->references('modulo_accion_id')->on('modulo_accions');
            $table->foreign('rol_id')->references('rol_id')->on('rols');
            $table->primary(array('modulo_accion_id', 'rol_id'));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rol_maccions');
    }
}
