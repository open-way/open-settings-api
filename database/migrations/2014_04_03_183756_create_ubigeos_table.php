<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbigeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubigeos', function (Blueprint $table) {
            $table->bigInteger('ubigeo_id')->unsigned();
            // $table->string('pais', 20)->comment('Pais');
            // $table->string('depto', 20)->comment('Departamento');
            // $table->string('pvcia', 20)->comment('Provincia');
            // $table->string('ditto', 20)->comment('Distrito');
            $table->string('nombre', 150);
            $table->primary(array('ubigeo_id'));

            $table->bigInteger('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('ubigeo_id')->on('ubigeos');
            
            // $table->primary('ubigeo_id');

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
        Schema::dropIfExists('ubigeos');
    }
}
