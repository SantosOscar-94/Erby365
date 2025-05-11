<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRTransportista extends Model
{
    use HasFactory;

    protected $table = 'gr_transportistas';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'warehouseId',
        'issue_date',
        'transfer_date',
        'measurement_unit',
        'weight',
        'quantity',
        'quantity_packages',
        'observations',
        'vehicular_config',
        'purchase_order',
        'bussinesId',
        'customerId',
        'senderId',
        'start_pointId',
        'destination_pointId',
        'end_pointId',
        'vehicleId',
        'driverId',
        'vehicle2Id',
        'driver2Id',
    ];
}
