<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPagoArancelToProcesoEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procesos_estudiantes', function (Blueprint $table) {
            $table->boolean('pago_arancel')->default(false)->after('proceso_id');
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
