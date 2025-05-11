<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KardexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->integer('warehouseId');
            $table->integer('documentTypeId');
            $table->string('document');
            $table->string('product');
            $table->integer('cant1')->nullable();
            $table->integer('price1')->nullable();
            $table->integer('total1')->nullable();
            $table->integer('cant2')->nullable();
            $table->integer('price2')->nullable();
            $table->integer('total2')->nullable();
            $table->integer('cant3')->nullable();
            $table->integer('price3')->nullable();
            $table->integer('total3')->nullable();
            $table->enum('tipo', ['Venta', 'Compra', 'Entrada', 'Salida']);
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
        Schema::dropIfExists('kardex');
    }
}
