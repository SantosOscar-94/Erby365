<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGRRemitentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gr_remitentes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('warehouseId');
            $table->dateTime('issue_date');
            $table->dateTime('transfer_date');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('receiver_id'); //tambn es clientId
            $table->string('transfer_mode');
            $table->string('reason_transfer');
//            $table->text('description_reason_transfer')->nullable();
            $table->unsignedInteger('measurement_unit');
            $table->unsignedDecimal('weight');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('quantity_packages');
            $table->text('vehicle_config')->nullable();
            $table->text('observations')->nullable();
            $table->string('purchase_order')->nullable();
            $table->string('other_purchase_order')->nullable();
            $table->string('purchase_order_reference')->nullable();
            $table->string('start_point');
            $table->string('end_point');
            $table->unsignedInteger('driverId');
            $table->unsignedInteger('vehicleId');
            $table->unsignedInteger('driverId2');
            $table->unsignedInteger('vehicleId2');
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
        Schema::dropIfExists('gr_remitentes');
    }
}
