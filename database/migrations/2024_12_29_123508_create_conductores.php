<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->id();
            $table->integer('iddoc');
            $table->string('dni_ruc', 15);
            $table->string('nombres');
            $table->string('direccion')->nullable();
            $table->string('licencia');
            $table->string('codigo_pais', 2);
            $table->string('ubigeo', 6)->nullable();
            $table->string('telefono', 100)->nullable();
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
        Schema::dropIfExists('conductores');
    }
}
