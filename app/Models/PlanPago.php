<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    use HasFactory;

    // Especificamos la tabla para evitar problemas de pluralización en Laravel
    protected $table = 'plan_pagos'; 

    protected $fillable = [
        'id_venta',
        'cantidad_cuotas',
        'monto_cuota',
        'total_deuda',
        'saldo_pendiente',
        'fecha_inicio',
        'estado_plan',
        'state'
    ];

    // Relación: Un plan de pago pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    // Relación: Un plan de pago tiene muchas cuotas
    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'id_plan_pago');
    }
}