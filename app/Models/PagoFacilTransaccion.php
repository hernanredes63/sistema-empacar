<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoFacilTransaccion extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'request_json' => 'array',
        'response_json' => 'array',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];
}