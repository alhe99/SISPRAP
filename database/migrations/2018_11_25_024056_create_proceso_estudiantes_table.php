<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcesoEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos_estudiantes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('estudiante_id')->unsigned()->nullable(false);
            $table->integer('proceso_id')->unsigned()->nullable(false);
            $table->boolean('estado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proceso_estudiantes');
    }
}
