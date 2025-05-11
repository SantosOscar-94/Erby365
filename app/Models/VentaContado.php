<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaContado extends Model
{
    use HasFactory;
    protected $table        = 'ventas_contado';
    protected $fillable = [
        'idventa_general',
        'metodo_pago',
        'destino',
        'referencia',
        'glosa',
        'monto'
    ];
}
