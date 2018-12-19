<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHoursToRealizeToGestionProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gestion_proyectos', function (Blueprint $table) {
            $table->integer('horas_a_realizar')->nullable(false)->after('horas_realizadas');
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
