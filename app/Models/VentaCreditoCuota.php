<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCreditoCuota extends Model
{
    use HasFactory;
    protected $table = 'venta_creditos_cuotas';
    protected $fillable = [
        'idventa_general',
        'numero_cuotas',
        'valor_cuotas',
        'cuota_inicial'
    ];
}
