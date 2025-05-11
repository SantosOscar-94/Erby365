<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGRTransportista extends Model
{
    use HasFactory;

    protected $table = 'detail_gr_transportistas';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'gr_transportistaId',
        'productId',
        'quantity',
        'weight',
    ];
}
