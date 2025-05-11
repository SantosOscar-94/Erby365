<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditQuote extends Model
{
    use HasFactory;
    protected $table        = 'credit_quotes';
    protected $primaryKey   = 'id';

    protected $fillable     =
    [
        'creditId',
        'cuentaId',
        'payId',
        'total',
    ];
}
