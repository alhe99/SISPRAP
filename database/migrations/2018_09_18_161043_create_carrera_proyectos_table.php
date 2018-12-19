<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarreraProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_proyectos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carrera_id')->unsigned()->nullable(false);;
            $table->integer('proyecto_id')->unsigned()->nullable(false);;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_proyectos');
    }
}
