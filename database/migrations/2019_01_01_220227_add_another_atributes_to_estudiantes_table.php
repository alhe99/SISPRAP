<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnotherAtributesToEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->char('estado_ss',1)->default("P");
            $table->char('estado_pp',1)->default("P");
            //P == Pendiente
            //I == Iniciado
            //F == Finalizado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            //
        });
    }
}
