<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailVentaGeneral extends Model
{
    use HasFactory;
    protected $table        = 'detail_ventas_generales';
    protected $primaryKey   = 'id';
    protected $fillable     =
    [
        'idventa_general',
        'idproducto',
        'cantidad',
        'precio_unitario',
        'precio_total',
        'idalmacen'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id');
    }

    public function ventaGeneral()
    {
        return $this->belongsTo(VentaGeneral::class, 'idventa_general');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id');
    }
}
