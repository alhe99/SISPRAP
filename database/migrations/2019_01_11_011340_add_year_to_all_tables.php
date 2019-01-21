<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('carreras', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('carrera_proyectos', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('constancias_entregadas', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('documentos', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('documentos_entregados', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('estudiantes', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('gestion_proyectos', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('img_supervisiones', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('instituciones', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('pago_aranceles', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('preinscripciones_proyectos', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('procesos_estudiantes', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('procesos_instituciones', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('proyectos', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('supervisiones_proyectos', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });
      Schema::table('usuarios', function (Blueprint $table) {
        $table->date('fecha_registro')->nullable(true);
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
  }
