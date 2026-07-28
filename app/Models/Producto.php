<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'id_categoria',
        'codigo',
        'nombre',
        'descripcion',
        'precio_compra', // Agregado según diseño de BD
        'precio_venta',  // Agregado según diseño de BD
        'stock_actual',  // Agregado según diseño de BD
        'stock_minimo',  // Agregado según diseño de BD
        'imagen',        // Agregado según diseño de BD
        'state',
    ];

    // Relación: Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}