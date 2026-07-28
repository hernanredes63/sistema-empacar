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
     * Muestra los detalles de una solicitud de compra específica.
     */
    public function show($id)
    {
        // 1. Buscamos la cabecera de la compra junto con los datos de su proveedor
        $compra = Compra::with('proveedor')->findOrFail($id);

        // 2. Buscamos el detalle de los productos comprados. 
        // Usamos un 'join' con la tabla productos para traer el código y el nombre.
        $detalles = DetalleCompra::where('id_compra', $id)
            ->join('productos', 'detalle_compras.id_producto', '=', 'productos.id')
            ->select('detalle_compras.*', 'productos.codigo', 'productos.nombre')
            ->get();

        // 3. Renderizamos la vista enviando los datos a Vue.js a través de Inertia
        return Inertia::render('Compras/Show', [
            'compra' => $compra,
            'detalles' => $detalles
        ]);
    }








    

    /**
     * Elimina lógicamente una compra (Cambia state a 'i')
     */
    /**
     * Elimina lógicamente una compra y revierte el stock del inventario.
     */
    public function destroy(Compra $compra)
    {
        try {
            DB::transaction(function () use ($compra) {
                
                // 1. Eliminación lógica de la cabecera de la compra
                $compra->state = 'i';
                $compra->estado_compra = 'Anulada'; 
                $compra->save();

                // 2. Traer todos los detalles asociados a esta compra
                $detalles = DetalleCompra::where('id_compra', $compra->id)->get();

                foreach ($detalles as $detalle) {
                    
                    // A. Eliminación lógica del detalle
                    $detalle->state = 'i';
                    $detalle->save();

                    // B. Restar el stock en la tabla 'productos'
                    $producto = Producto::find($detalle->id_producto);
                    $producto->stock_actual -= $detalle->cantidad;
                    $producto->save();

                    // C. Registrar el movimiento de salida en 'inventarios' para mantener trazabilidad
                    Inventario::create([
                        'id_producto' => $detalle->id_producto,
                        'tipo_movimiento' => 'salida',
                        'cantidad' => $detalle->cantidad,
                        'stock_actual' => $producto->stock_actual,
                        'fecha_movimiento' => now(),
                        'descripcion' => 'Salida por anulación de la compra #' . $compra->id,
                        'origen_tipo' => 'anulacion_compra',
                        'origen_id' => $compra->id,
                        'state' => 'a'
                    ]);
                }
            });

            return redirect()->route('compras.index')
                             ->with('success', 'Compra anulada y stock revertido correctamente.');

        } catch (\Exception $e) {
            // Si algo falla, revertimos y avisamos al usuario
            return back()->with('error', 'Ocurrió un error al anular la compra: ' . $e->getMessage());
        }
    }

    // Los métodos show, edit, update y destroy los puedes dejar vacíos por ahora
}