<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_venta',
        'id_cliente',
        'total',
        'tipo_venta',     // 'contado' o 'credito'
        'estado_venta',   // 'Completada', 'Pendiente', 'Anulada'
        'observacion',
        'pagofacil_transaction_id',
        'state'
    ];

    // Relación: Una venta pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // Relación: Una venta tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }
}