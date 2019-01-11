<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHorasToProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->integer('horas_realizar')->nullable(false)->after('proceso_id');
            $table->integer('cantidades_vacantes')->nullable(false)->after('horas_realizar');
            $table->char('estado_vacantes',1)->nullable(false)->after('cantidades_vacantes')->default('D');
            $table->char('tipo_proyecto',1)->default('I')->nullable(false)->after('estado_vacantes');
        });

        //*********** SIMBOLOGIA ***********//
        // estado_vacantes:
        // D = disponible
        // C = completado
        // tipo_proyecto:
        // I = Interno
        // E = Externo
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            //
        });
    }
}
