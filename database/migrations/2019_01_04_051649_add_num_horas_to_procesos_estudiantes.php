<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumHorasToProcesosEstudiantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procesos_estudiantes', function (Blueprint $table) {
            $table->integer('num_horas')->nullable(true)->after('pago_arancel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procesos_estudiantes', function (Blueprint $table) {
            //
        });
    }
}
