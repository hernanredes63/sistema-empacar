<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_compra',
        'id_proveedor',
        'total',
        'estado_compra',
        'observacion',
        'state'
    ];

    // Relación: Una compra pertenece a un proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    // Relación: Una compra tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra');
    }
}