<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteReclamos extends Model
{
    use HasFactory;
    protected $table        = 'ReporteReclamos';
    protected $primaryKey   = 'id';
    protected $fillable     =
    [
        'documentTypeId',
        'userId',
        'warehouseId',
        'document',
        'product',
        'cant1',
        'price1',
        'total1',
        'cant2',
        'price2',
        'total2',
        'cant3',
        'price3',
        'total3',
        'tipo'
    ];
}
