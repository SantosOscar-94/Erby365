<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaCreditosCuotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta_creditos_cuotas', function (Blueprint $table) {
            $table->id();
            $table->integer('idventa_general');
            $table->integer('numero_cuotas');
            $table->decimal('valor_cuotas', 18, 2);
            $table->enum('cuota_inicial', ['0','1']);
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
        Schema::dropIfExists('venta_creditos_cuotas');
    }
}
