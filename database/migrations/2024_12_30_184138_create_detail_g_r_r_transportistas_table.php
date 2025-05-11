<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailGRRTransportistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_gr_transportistas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gr_transportistaId');
            $table->unsignedBigInteger('productId');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('weight');
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
        Schema::dropIfExists('detail_gr_transportistas');
    }
}
