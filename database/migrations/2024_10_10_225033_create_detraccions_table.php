<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetraccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detraccions', function (Blueprint $table) {
            $table->id();
            $table->integer('idventa_general');
            $table->integer('idproducto');
            $table->string('iddetraccion');
            $table->string('num_detracciones');
            $table->string('num_constancia_pago')->nullable();
            $table->decimal('monto_detraccion', 18, 2);
            $table->string('metodo_pago');
            $table->string('path_imagen_constancia');
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
        Schema::dropIfExists('detraccions');
    }
}
