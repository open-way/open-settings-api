<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuloAccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_accions', function (Blueprint $table) {
            $table->bigIncrements('modulo_accion_id');
            $table->string('nombre', 150);
            $table->string('llave', 50);

            $table->bigInteger('modulo_id')->nullable()->unsigned();
            $table->foreign('modulo_id')->references('modulo_id')->on('modulos');

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
        Schema::dropIfExists('modulo_accions');
    }
}
