<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoGpToGestionProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gestion_proyectos', function (Blueprint $table) {
            $table->integer('tipo_gp')->nullable(true)->after('observacion_final');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gestion_proyectos', function (Blueprint $table) {
            //
        });
    }
}
