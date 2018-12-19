<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         
        Schema::create('usuarios', function (Blueprint $table) {
           
            $table->integer('id')->unsigned();
            $table->string('nombre',150)->nullable(true);
            $table->string('usuario',50)->unique()->nullable(false);
            $table->string('password',80)->nullable(false);
            $table->boolean('estado')->default(1);
            $table->integer('rol_id')->unsigned();
            $table->rememberToken();
            //$table->foreign('idrol')->references('id')->on('roles');
            // $table->rememberToken();
            //$table->timestamps();
            //$table->foreign('id')->references('id')->on('personas')->onDelete('cascade');            
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
