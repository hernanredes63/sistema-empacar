<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_compra',
        'id_producto',
        'cantidad',
        'precio_compra',
        'subtotal',
        'state'
    ];

    // Relación: Este detalle pertenece a una compra específica
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }

    // Relación: Este detalle está asociado a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}