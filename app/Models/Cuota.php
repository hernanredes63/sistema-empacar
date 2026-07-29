<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_plan_pago',
        'numero_cuota',
        'fecha_vencimiento',
        'monto',
        'estado_cuota', // 'pendiente', 'pagada', 'vencida'
        'fecha_pago',
        'pagofacil_transaction_id',
        'state'
    ];

    // Relación: Una cuota pertenece a un plan de pago
    public function planPago()
    {
        return $this->belongsTo(PlanPago::class, 'id_plan_pago');
    }
}