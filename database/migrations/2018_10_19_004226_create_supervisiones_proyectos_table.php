<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisionesProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisiones_proyectos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha')->nullable(false);
            $table->text('observacion')->nullable(true);
            $table->boolean('estado')->default(1); 
            $table->integer('proyecto_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supervisiones_proyectos');
    }
}
