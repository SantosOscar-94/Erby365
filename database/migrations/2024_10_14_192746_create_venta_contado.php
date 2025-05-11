<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaContado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta_contado', function (Blueprint $table) {
            $table->id();
            $table->integer('idventa_general');
            $table->string('metodo_pago');
            $table->string('destino');
            $table->string('referencia');
            $table->string('glosa');
            $table->decimal('monto', 18, 2);
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
        Schema::dropIfExists('venta_contado');
    }
}
