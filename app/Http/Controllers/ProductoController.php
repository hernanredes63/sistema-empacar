<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Muestra la lista de productos activos.
     */
    public function index()
    {
        // Solo traemos los productos activos ('a') aplicando eliminación lógica
        $productos = Producto::with('categoria')
            ->where('state', 'a')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Productos/Index', [
            'productos' => $productos
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        // Solo traemos las categorías activas
        $categorias = Categoria::where('state', 'a')->get();

        return Inertia::render('Productos/Create', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Guarda el nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos todos los campos requeridos según la base de datos
        $request->validate([
            'codigo' => 'required|string|max:100|unique:productos,codigo',
            'nombre' => 'required|string|max:150',
            'id_categoria' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0', // <-- CAMBIA A NULLABLE
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        // 2. Guardamos forzando el estado a 'a' (Activo)
        Producto::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'id_categoria' => $request->id_categoria,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra ?? 0,
            'precio_venta' => $request->precio_venta ?? 0, // <-- AGREGA EL ?? 0
            'stock_actual' => $request->stock_actual ?? 0,
            'stock_minimo' => $request->stock_minimo ?? 0,
            'state' => 'a', // <-- ¡Esto soluciona tu problema en Compras!
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto registrado correctamente.');
    }

    /**
     * Muestra el formulario para editar un producto.
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('state', 'a')->get();

        return Inertia::render('Productos/Edit', [
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Actualiza el producto en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:100|unique:productos,codigo,' . $id,
            'nombre' => 'required|string|max:150',
            'id_categoria' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        $producto = Producto::findOrFail($id);
        
        $producto->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'id_categoria' => $request->id_categoria,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra ?? 0,
            'precio_venta' => $request->precio_venta ?? 0, // <-- AGREGA EL ?? 0
            'stock_actual' => $request->stock_actual ?? 0,
            'stock_minimo' => $request->stock_minimo ?? 0,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Elimina el producto de la base de datos de forma LÓGICA.
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        
        // Aplicando la eliminación lógica en lugar del ->delete() físico
        $producto->update(['state' => 'i']);

        return redirect()->route('productos.index')->with('success', 'Producto eliminado lógicamente.');
    }
}