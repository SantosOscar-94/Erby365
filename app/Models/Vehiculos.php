<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculos extends Model
{
    use HasFactory;
    protected $table        = 'vehiculos';
    protected $primaryKey   = 'id';

    protected $fillable     =
    [
        'num_placa',
        'tuc',
        'autori_placa_principal',
        'placa_secundario',
        'tuc_placa_secundario',
        'autori_placa_secundario',
        'modelo',
        'marca'
        

       
    ];
}
