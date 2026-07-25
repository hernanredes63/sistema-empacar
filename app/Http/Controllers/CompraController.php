<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\DetalleCompra;
use App\Models\Inventario; // Importante para actualizar el Kardex
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Muestra la lista principal de compras.
     */
    public function index()
    {
        // Traemos las compras con su respectivo proveedor activo
        $compras = Compra::with('proveedor')
            ->where('state', 'a')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Compras/Index', [
            'compras' => $compras
        ]);
    }

    /**
     * Muestra el formulario para registrar una nueva solicitud de compra.
     */
    public function create()
    {
        // Necesitamos enviar los proveedores y productos activos a Vue
        // para que el usuario pueda seleccionarlos en el formulario.
        $proveedores = Proveedor::where('state', 'a')->get();
        $productos = Producto::where('state', 'a')->get();

        return Inertia::render('Compras/Create', [
            'proveedores' => $proveedores,
            'productos' => $productos
        ]);
    }

    /**
     * Guarda la nueva compra, sus detalles y actualiza el inventario.
     */
    public function store(Request $request)
    {
        // 1. Validación estricta de los datos entrantes
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'observacion' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_compra' => 'required|numeric|min:0',
        ]);

        try {
            // 2. Iniciar Transacción
            DB::transaction(function () use ($request) {
                
                // Calcular el monto total sumando (cantidad * precio) de cada producto
                $total = 0;
                foreach ($request->productos as $item) {
                    $total += $item['cantidad'] * $item['precio_compra'];
                }

                // 3. Insertar el registro principal en la tabla 'compras'
                $compra = Compra::create([
                    'fecha_compra' => $request->fecha_compra,
                    'id_proveedor' => $request->id_proveedor,
                    'total' => $total,
                    'estado_compra' => 'Completada', // Se asume ingreso directo
                    'observacion' => $request->observacion,
                    'state' => 'a'
                ]);

                // 4. Procesar la lista de productos
                foreach ($request->productos as $item) {
                    $subtotal = $item['cantidad'] * $item['precio_compra'];

                    // A. Guardar en 'detalle_compras'
                    DetalleCompra::create([
                        'id_compra' => $compra->id,
                        'id_producto' => $item['id_producto'],
                        'cantidad' => $item['cantidad'],
                        'precio_compra' => $item['precio_compra'],
                        'subtotal' => $subtotal,
                        'state' => 'a'
                    ]);

                    // B. Sumar el stock en la tabla 'productos'
                    $producto = Producto::find($item['id_producto']);
                    $producto->stock_actual += $item['cantidad'];
                    $producto->precio_compra = $item['precio_compra']; // Actualiza al último costo
                    $producto->save();

                    // C. Registrar el movimiento de entrada en 'inventarios'
                    Inventario::create([
                        'id_producto' => $item['id_producto'],
                        'tipo_movimiento' => 'entrada',
                        'cantidad' => $item['cantidad'],
                        'stock_actual' => $producto->stock_actual,
                        'fecha_movimiento' => now(),
                        'descripcion' => 'Entrada por compra interna #' . $compra->id,
                        'origen_tipo' => 'compra',
                        'origen_id' => $compra->id,
                        'state' => 'a'
                    ]);
                }
            });

            // Si todo sale bien, redirigimos a la tabla principal con mensaje de éxito
            return redirect()->route('compras.index')
                             ->with('success', 'Compra registrada y stock actualizado con éxito.');

        } catch (\Exception $e) {
            // Si algo falla, revertimos los cambios y avisamos al usuario
            return back()->with('error', 'Ocurrió un error al procesar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Elimina lógicamente una compra (Cambia state a 'i')
     */
    public function destroy(Compra $compra)
    {
        // Aplicando la eliminación lógica según requisitos (state = 'i')
        $compra->state = 'i';
        $compra->save();

        return redirect()->route('compras.index')->with('success', 'Compra eliminada lógicamente.');
    }

    // Los métodos show, edit, update y destroy los puedes dejar vacíos por ahora
}