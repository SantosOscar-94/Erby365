<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListadoDetracciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listado_detracciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_operacion');
            $table->string('codigo');
            $table->string('descripcion');
            $table->string('porcentaje');
            //$table->enum('estado_detra', ['on', 'off']);
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
        Schema::dropIfExists('listado_detracciones');
    }
}
