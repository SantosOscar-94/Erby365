<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Arreglar aquí ... hay más campos
        Schema::create('buys', function (Blueprint $table) {
            $table->id();
            $table->integer('idtipo_comprobante');
            $table->string('serie');
            $table->string('correlativo');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->time('hora');
            $table->integer('idproveedor');
            $table->integer('idmoneda');
            $table->integer('idpago');
            $table->integer('modo_pago');
            $table->decimal('exonerada', 18, 2);
            $table->decimal('inafecta', 18, 2);
            $table->decimal('gravada', 18, 2);
            $table->decimal('anticipo', 18, 2);
            $table->decimal('igv', 18, 2);
            $table->decimal('gratuita', 18, 2);
            $table->decimal('otros_cargos', 18, 2);
            $table->decimal('total', 18, 2);
            $table->string('observaciones')->nullable();
            $table->integer('estado')->nullable();
            $table->integer('idusuario');
            $table->integer('idcaja');
            $table->integer('idalmacen')->nullable();
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
        Schema::dropIfExists('buys');
    }
}
