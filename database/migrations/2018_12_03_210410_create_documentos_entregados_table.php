<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosEntregadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_entregados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gestion_proyecto_id')->unsigned();
            $table->integer('documento_id')->unsigned();
            $table->text('estado');
            $table->text('observacion')->nullable(true);
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
        Schema::dropIfExists('documentos_entregados');
    }
}
