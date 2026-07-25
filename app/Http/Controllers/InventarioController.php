<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consultamos los movimientos de inventario incluyendo los datos del producto asociado
        $inventarios = Inventario::with('producto')->orderBy('created_at', 'desc')->get();
        
        // Consultamos los productos activos para usarlos al registrar un nuevo movimiento
        $productos = Producto::where('state', 'a')->get();

        // Enviamos los datos al componente Vue a través de Inertia[cite: 2]
        return Inertia::render('Inventarios/Index', [
            'inventarios' => $inventarios,
            'productos' => $productos
        ]);
    }



    public function create()
    {
        // Enviamos los productos disponibles al formulario
        $productos = Producto::where('state', 'a')->get();
        return Inertia::render('Inventarios/Create', [
            'productos' => $productos
        ]);
    }

    public function store(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'id_producto' => 'required|exists:productos,id',
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $producto = Producto::findOrFail($request->id_producto);
        $nuevo_stock = $producto->stock_actual;

        // Actualización matemática del stock
        if ($request->tipo_movimiento === 'salida') {
            $nuevo_stock -= $request->cantidad;
        } else {
            $nuevo_stock += $request->cantidad; // Entradas y ajustes suman
        }

        // Guardamos el nuevo stock en la tabla productos
        $producto->update(['stock_actual' => $nuevo_stock]);

        // Registramos el historial del movimiento en el inventario[cite: 2]
        Inventario::create([
            'id_producto' => $request->id_producto,
            'tipo_movimiento' => $request->tipo_movimiento,
            'cantidad' => $request->cantidad,
            'stock_actual' => $nuevo_stock,
            'fecha_movimiento' => now(),
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('inventarios.index');
    }


}