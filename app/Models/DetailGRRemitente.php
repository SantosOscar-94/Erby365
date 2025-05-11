<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGRRemitente extends Model
{
    use HasFactory;

    protected $table = 'detail_gr_remitentes';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'gr_remitenteId',
        'productId',
        'quantity',
        'weight',
    ];
}
