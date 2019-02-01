<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',190)->nullable(false);;
            $table->date('fecha')->nullable(false);
            $table->text('actividades')->nullable(false);
            $table->integer('institucion_id')->unsigned();
            $table->integer('proceso_id')->unsigned();
            $table->string('img')->nullable(true);;         
            $table->boolean('estado')->default(1);   
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
        Schema::dropIfExists('proyectos');
    }
}
