<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaDireccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_direccions', function (Blueprint $table) {
            $table->bigIncrements('persona_direccion_id');
            $table->string('referencia')->nullable();
            $table->string('map_latitud')->nullable();
            $table->string('map_longitud')->nullable();
            $table->boolean('activo')->default(true);
            
            $table->bigInteger('ubigeo_id')->nullable()->unsigned();
            $table->foreign('ubigeo_id')->references('ubigeo_id')->on('ubigeos');

            $table->bigInteger('persona_id')->unsigned();
            $table->foreign('persona_id')->references('persona_id')->on('personas');
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
        Schema::dropIfExists('persona_direccions');
    }
}
