<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',200)->nullable(false);;
            $table->text('direccion')->nullable(true);
            $table->string('telefono',14)->nullable(true);
            $table->string('email',100)->nullable(true);
            $table->integer('sector_institucion_id');
            $table->integer('municipio_id');
            $table->boolean('estado')->default(1);
            $table->timestamps();

            //$table->foreign('sector_institucion_id')->references('id')->on('sectores_instituciones');
            //$table->foreign('municipio_id')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituciones');
    }
}
