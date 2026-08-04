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

    // Relación indirecta: Una cuota pertenece a una Venta a través del Plan de Pago
    public function venta()
    {
        return $this->hasOneThrough(
            Venta::class,
            PlanPago::class,
            'id',
            'id',
            'id_plan_pago',
            'id_venta'
        );
    }

    // --- AGREGA ESTO AQUÍ ---
    public function saldo()
    {
        if ($this->estado_cuota === 'pagada') {
            return 0.0;
        }
        return (float) $this->monto;
    }
}