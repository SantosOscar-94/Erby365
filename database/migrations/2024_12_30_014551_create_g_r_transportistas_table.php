<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGRTransportistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gr_transportistas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('warehouseId');
            $table->dateTime('issue_date');
            $table->dateTime('transfer_date');
            $table->unsignedInteger('measurement_unit');
            $table->unsignedDecimal('weight');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('quantity_packages');
            $table->text('observations')->nullable();
            $table->text('vehicle_config')->nullable();
            $table->string('purchase_order')->nullable();
            $table->unsignedBigInteger('bussinesId');
            $table->unsignedBigInteger('customerId');
            $table->unsignedBigInteger('senderId');
            $table->unsignedBigInteger('start_pointId');
            $table->unsignedBigInteger('destination_pointId');
            $table->unsignedBigInteger('end_pointId');
            $table->unsignedBigInteger('vehicleId');
            $table->unsignedBigInteger('driverId');
            $table->unsignedBigInteger('vehicle2Id');
            $table->unsignedBigInteger('driver2Id');
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
        Schema::dropIfExists('gr_transportistas');
    }
}
