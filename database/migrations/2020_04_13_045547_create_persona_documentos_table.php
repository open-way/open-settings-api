<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_documentos', function (Blueprint $table) {
            $table->string('num_doc')->primary();
            $table->string('img_url')->nullable();

            $table->bigInteger('persona_id')->unsigned();
            $table->foreign('persona_id')->references('persona_id')->on('personas');

            $table->bigInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')->references('tipo_documento_id')->on('tipo_documentos');

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
        Schema::dropIfExists('persona_documentos');
    }
}
