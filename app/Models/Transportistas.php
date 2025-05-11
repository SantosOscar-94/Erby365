<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportistas extends Model
{
    use HasFactory;
    protected $table = 'transportistas';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'iddoc',
        'dni_ruc',
        'nombres',
        'direccion',
        'mtc',
        'codigo_pais',
        'ubigeo',
        'correo'
        
    ];
}
