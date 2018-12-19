<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',150)->nullable(false);
            $table->string('apellido',150)->nullable(false);
            $table->date('fechaNac')->nullable(true);
            $table->string('genero',1)->nullable(false);
            $table->string('telefono',14);
            $table->string('codCarnet',15)->nullable(false);
            $table->string('email',100)->nullable(true);
            $table->text('direccion')->nullable(true);
            $table->integer('tipo_beca_id')->nullable(true)->unsigned();
            $table->boolean('estado')->default(1);
            //$table->integer('nivel_academico_id')->unsigned();
            $table->integer('carrera_id')->unsigned();
            $table->integer('municipio_id')->unsigned();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
}
