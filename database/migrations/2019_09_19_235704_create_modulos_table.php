<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->bigIncrements('modulo_id');
            $table->boolean('activo')->default(true);
            $table->string('icon', 50)->nullable();
            $table->string('link', 150)->nullable();
            $table->string('title', 150);
            $table->integer('order')->default(0);
            $table->boolean('group')->default(false);
            $table->boolean('home')->default(false);
            $table->boolean('is_mobile')->default(false);

            $table->string('codigo', 50)->unique()->nullable();

            $table->bigInteger('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('modulo_id')->on('modulos');
            
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
        Schema::dropIfExists('modulos');
    }
}
