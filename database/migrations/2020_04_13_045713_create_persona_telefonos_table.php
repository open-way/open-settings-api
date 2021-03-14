<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTelefonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_telefonos', function (Blueprint $table) {
            $table->bigIncrements('persona_telefono_id');
            $table->string('num_telefono')->nullable();
            $table->boolean('es_privado')->default(false);
            $table->boolean('activo')->default(true);

            $table->bigInteger('persona_id')->unsigned();
            $table->foreign('persona_id')->references('persona_id')->on('personas');
            
            $table->bigInteger('tipo_telefono_id')->unsigned();
            $table->foreign('tipo_telefono_id')->references('tipo_telefono_id')->on('tipo_telefonos');


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
        Schema::dropIfExists('persona_telefonos');
    }
}
