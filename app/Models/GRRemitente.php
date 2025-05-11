<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRRemitente extends Model
{
    use HasFactory;

    protected $table = 'gr_remitentes';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'warehouseId',
        'issue_date',
        'transfer_date',
        'client_id',
        'receiver_id',
        'transfer_mode',
        'reason_transfer',
//        'description_reason_transfer',
        'measurement_unit',
        'weight',
        'quantity',
        'quantity_packages',
        'vehicular_config',
        'observations',
        'purchase_order',
        'other_purchase_order',
        'purchase_order_reference',
        'start_point',
        'end_point',
        'driverId',
        'vehicleId',
        'driverId2',
        'vehicleId2',
    ];
}
