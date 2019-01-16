<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoProyectoToPreinscripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preinscripciones_proyectos', function (Blueprint $table) {
             // $table->char('tipo_proyecto',1)->default("I"); //I = Proyecto Interno, E = Proyecto Externo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preinscripciones_proyectos', function (Blueprint $table) {
            //
        });
    }
}
