<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion_Partida extends Model
{
    use HasFactory;
    protected $table        = 'direccion_Partida';
    protected $primaryKey   = 'id';

    protected $fillable     =
    [
        'direccion',
        'ubigeo'
        

       
    ];
}
