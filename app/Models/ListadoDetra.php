<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListadoDetra extends Model
{
    use HasFactory;
    protected $table = 'listado_detracciones';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'tipo_operacion',
        'codigo',
        'descripcion',
        'porcentaje',
    ];
}
