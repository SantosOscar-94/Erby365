<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detraccion extends Model
{
    use HasFactory;
    protected $table        = 'detraccions';
    protected $fillable     =
    [
        'idventa_general',
        'idproducto',
        'num_detracciones',
        'num_constancia_pago',
        'iddetraccion',
        'metodo_pago',
        'monto_detraccion',
        'path_imagen_constancia'
    ];
}
