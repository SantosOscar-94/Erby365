<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaGeneral extends Model
{
    use HasFactory;

    protected $table        = 'ventas_generales';
    protected $primaryKey   = 'id';
    protected $fillable     =
    [
        'idtipo_comprobante',
        'serie',
        'correlativo',
        'fecha_emision',
        'fecha_vencimiento',
        'hora',
        'tipo_operacion',
        'idcliente',
        'idmoneda',
        'tipo_cambio',
        'exonerada',
        'inafecta',
        'gravada',
        'anticipo',
        'igv',
        'gratuita',
        'otros_cargos',
        'total',
        'cdr',
        'estado',
        'idusuario',
        'idcaja',
        'condicion_pago'
    ];

    public function creditQuote()
    {
        return $this->hasMany(CreditQuote::class, 'creditId');
    }

    public function detailVentasGenerales(){
        return $this->hasMany(DetailVentaGeneral::class, 'idventa_general');
    }
}
