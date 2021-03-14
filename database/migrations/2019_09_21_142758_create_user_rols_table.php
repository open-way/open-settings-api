<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rols', function (Blueprint $table) {
            
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('rol_id')->unsigned();
            $table->boolean('activo')->default(true);
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('rol_id')->references('rol_id')->on('rols');
            $table->primary(array('user_id', 'rol_id'));

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
        Schema::dropIfExists('user_rols');
    }
}
