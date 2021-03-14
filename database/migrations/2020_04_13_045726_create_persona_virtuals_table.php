<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaVirtualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_virtuals', function (Blueprint $table) {
            $table->bigIncrements('persona_virtual_id');
            $table->string('direccion')->nullable();
            $table->boolean('activo')->default(true);

            $table->bigInteger('persona_id')->unsigned();
            $table->foreign('persona_id')->references('persona_id')->on('personas');
            
            $table->bigInteger('tipo_virtual_id')->unsigned();
            $table->foreign('tipo_virtual_id')->references('tipo_virtual_id')->on('tipo_virtuals');
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
        Schema::dropIfExists('persona_virtuals');
    }
}
