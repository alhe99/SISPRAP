<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGestionProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion_proyectos', function (Blueprint $table) {

            $table->increments('id');
            $table->date('fecha_inicio')->nullable(false);
            $table->date('fecha_fin')->nullable(true);
            $table->integer('horas_realizadas')->nullable(true)->default(0);
            $table->integer('proyecto_id')->unsigned();
            $table->integer('estudiante_id')->unsigned();
            $table->char('estado',1)->default('I');
            $table->string('nombre_supervisor',100)->nullable(true);
            $table->string('tel_supervisor',14)->nullable(true);
            $table->text('observacion_final')->nullable(true);;
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
        Schema::dropIfExists('gestion_proyectos');
    }
}
