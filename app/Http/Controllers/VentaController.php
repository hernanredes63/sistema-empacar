<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        // Traemos las ventas con el cliente asociado para el historial
        $ventas = Venta::with('cliente')->where('state', 'a')->orderBy('id', 'desc')->get();
        return Inertia::render('Ventas/Index', ['ventas' => $ventas]);
    }

    public function create()
    {
        // Enviamos clientes y productos activos con stock mayor a 0 a la vista de Vue
        $clientes = Cliente::where('state', 'a')->get();
        $productos = Producto::where('state', 'a')->where('stock_actual', '>', 0)->get();

        return Inertia::render('Ventas/Create', [
            'clientes' => $clientes,
            'productos' => $productos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'tipo_venta' => 'required|string',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_venta' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $total = 0;

                // 1. Validar stock y calcular total
                foreach ($request->productos as $item) {
                    $producto = Producto::findOrFail($item['id_producto']);
                    if ($producto->stock_actual < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                    }
                    $total += $item['cantidad'] * $item['precio_venta'];
                }

                // 2. Crear cabecera de Venta
                $venta = Venta::create([
                    'fecha_venta' => $request->fecha_venta,
                    'id_cliente' => $request->id_cliente,
                    'total' => $total,
                    'tipo_venta' => $request->tipo_venta,
                    'estado_venta' => $request->tipo_venta === 'contado' ? 'Completada' : 'Pendiente',
                    'observacion' => $request->observacion,
                    'state' => 'a'
                ]);

                // 3. Procesar detalle y descontar inventario
                foreach ($request->productos as $item) {
                    $producto = Producto::find($item['id_producto']);
                    $subtotal = $item['cantidad'] * $item['precio_venta'];

                    // Crear detalle
                    DetalleVenta::create([
                        'id_venta' => $venta->id,
                        'id_producto' => $item['id_producto'],
                        'cantidad' => $item['cantidad'],
                        'precio_venta' => $item['precio_venta'],
                        'subtotal' => $subtotal,
                        'state' => 'a'
                    ]);

                    // Descontar stock
                    $producto->stock_actual -= $item['cantidad'];
                    $producto->save();

                    // Registrar salida en Inventario (Kardex)
                    Inventario::create([
                        'id_producto' => $item['id_producto'],
                        'tipo_movimiento' => 'salida',
                        'cantidad' => $item['cantidad'],
                        'stock_actual' => $producto->stock_actual,
                        'fecha_movimiento' => now(),
                        'descripcion' => 'Salida por venta #' . $venta->id,
                        'origen_tipo' => 'venta',
                        'origen_id' => $venta->id,
                        'state' => 'a'
                    ]);
                }
            });

            return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}