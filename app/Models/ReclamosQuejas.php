<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReclamosQuejas extends Model
{
    use HasFactory;
    protected $table        = 'reclamos_quejas';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'tipo_documento',
        'documento_cliente',
        'nombre_cliente',
        'apellido_paterno',
        'apellido_materno',
        'direccion',
        'correo',
        'telefono',
        'edad',
        'padre_madre',
        'fecha_incidente',
        'canal_compra',
        'bien_contratado',
        'monto',
        'direccion_tienda',
        'tienda',
        'descripcion_item',
        'op_queja_reclamo',
        'op_motivo',
        'detalle_reclamo',
        'pedido_realizado_a',
        'observaciones',
        'file_evidencia_path'
    ];

}
