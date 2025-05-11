<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductores extends Model
{
    use HasFactory;
    protected $table = 'conductores';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'iddoc',
        'dni_ruc',
        'nombres',
        'direccion',
        'licencia',
        'codigo_pais',
        'ubigeo',
        'correo',
        'telefono'
    ];
}
