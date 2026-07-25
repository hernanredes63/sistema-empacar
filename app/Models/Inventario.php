<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_producto',
        'tipo_movimiento',
        'cantidad',
        'stock_actual',
        'fecha_movimiento',
        'descripcion',
        'origen_tipo',
        'origen_id',
        'state',
    ];

    // Relación: Muchos movimientos de inventario pertenecen a un producto[cite: 2]
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}