<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;
    protected $table = 'cashes';
    protected $primaryKey = 'id';

    protected $fillable = 
    [
        'descripcion'
    ];
}
