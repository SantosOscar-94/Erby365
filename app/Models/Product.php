<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table        = 'products';
    protected $primaryKey   = 'id';

    protected $fillable =
    [
        'codigo_interno',
        'codigo_barras',
        'codigo_sunat',
        'descripcion',
        'marca',
        'presentacion',
        'idunidad',
        'idcodigo_igv',
        'igv',
        'precio_compra',
        'precio_venta',
        'precio_venta_por_mayor',
        'precio_venta_distribuidor',
        'impuesto',
        'fecha_vencimiento',
        'opcion',
        'enable'
    ];
}
