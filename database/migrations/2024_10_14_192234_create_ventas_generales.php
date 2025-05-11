<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasGenerales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_generales', function (Blueprint $table) {
            $table->id();
            $table->integer('idtipo_comprobante');
            $table->string('serie');
            $table->string('correlativo', 8);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->time('hora');
            $table->string('tipo_operacion');
            $table->integer('idcliente');
            $table->integer('idmoneda');
            $table->string('tipo_cambio');
            $table->tinyInteger('cdr');
            $table->decimal('exonerada', 18, 2);
            $table->decimal('inafecta', 18, 2);
            $table->decimal('gravada', 18, 2);
            $table->decimal('anticipo', 18, 2);
            $table->decimal('igv', 18, 2);
            $table->decimal('gratuita', 18, 2);
            $table->decimal('otros_cargos', 18, 2);
            $table->decimal('total', 18, 2);
            $table->integer('estado')->nullable();
            $table->integer('idusuario');
            $table->integer('idcaja');
            $table->string('condicion_pago');
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
        Schema::dropIfExists('ventas_generales');
    }
}
