<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->bigIncrements('persona_id');
            $table->string('nombres', 50);
            $table->string('ap_paterno', 60);
            $table->string('ap_materno', 60)->nullable();
            $table->dateTime('fecha_nac')->nullable();
            $table->string('sexo', 1)->nullable();
            $table->string('estado_civil', 1)->default('S');
            // $table->string('img_url',200)->nullable();;

            $table->bigInteger('ubigeo_id')->nullable()->unsigned()->comment('Lugar de nacimiento');
            $table->foreign('ubigeo_id')->references('ubigeo_id')->on('ubigeos');
            // $table->primary(array('persona_id'));

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
        Schema::dropIfExists('personas');
    }
}
