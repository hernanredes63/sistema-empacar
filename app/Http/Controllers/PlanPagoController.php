<?php

namespace App\Http\Controllers;

use App\Models\PlanPago;
use App\Models\Cuota;
use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Fundamental para sumar meses a las fechas

class PlanPagoController extends Controller
{
    /**
     * Muestra el listado de planes de pago.
     */
    public function index()
    {
        // Traemos los planes con los datos de la venta y el cliente
        $planes = PlanPago::with(['venta.cliente'])->where('state', 'a')->orderBy('id', 'desc')->get();
        return Inertia::render('PlanPagos/Index', ['planes' => $planes]);
    }

    /**
     * Muestra el formulario para crear un plan.
     */
    
    public function create()
    {
        // 1. Obtenemos los IDs de las ventas que YA tienen un plan de pago
        $idsConPlan = PlanPago::where('state', 'a')->pluck('id_venta')->toArray();

        // 2. Buscamos las ventas, previniendo problemas de mayúsculas/minúsculas
        $ventasSinPlan = Venta::with('cliente')
            ->whereIn('tipo_venta', ['credito', 'Credito', 'CREDITO']) 
            ->whereIn('estado_venta', ['Pendiente', 'pendiente', 'PENDIENTE'])
            ->where('state', 'a')
            // 3. Excluimos las que ya tienen plan SOLO si el arreglo no está vacío
            ->when(!empty($idsConPlan), function ($query) use ($idsConPlan) {
                return $query->whereNotIn('id', $idsConPlan);
            })
            ->get();

        return Inertia::render('PlanPagos/Create', [
            'ventas' => $ventasSinPlan
        ]);
    }

    /**
     * Guarda el plan y genera las cuotas matemáticamente.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos de entrada
        $request->validate([
            'id_venta' => 'required|exists:ventas,id|unique:plan_pagos,id_venta', // unique evita 2 planes para 1 venta
            'cantidad_cuotas' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 2. Traemos la venta para saber cuánto nos deben
                $venta = Venta::findOrFail($request->id_venta);
                
                // 3. Matemática: Calculamos cuánto vale cada cuota
                // Usamos round() para evitar problemas de muchos decimales
                $montoCuota = round($venta->total / $request->cantidad_cuotas, 2);

                // 4. Creamos la cabecera del Plan de Pago
                $plan = PlanPago::create([
                    'id_venta' => $venta->id,
                    'cantidad_cuotas' => $request->cantidad_cuotas,
                    'monto_cuota' => $montoCuota,
                    'total_deuda' => $venta->total,
                    'saldo_pendiente' => $venta->total,
                    'fecha_inicio' => $request->fecha_inicio,
                    'estado_plan' => 'Pendiente',
                    'state' => 'a'
                ]);

                // 5. Generación automática de Cuotas usando un Bucle (Loop)
                $fechaVencimientoBase = Carbon::parse($request->fecha_inicio);

                for ($i = 1; $i <= $request->cantidad_cuotas; $i++) {
                    
                    // Calculamos la fecha: La cuota 1 vence en la fecha de inicio, 
                    // la cuota 2 al mes siguiente, la cuota 3 al segundo mes, etc.
                    // Clonamos la fecha base para no modificar la original en cada iteración
                    $fechaVencimiento = $fechaVencimientoBase->copy()->addMonths($i - 1);

                    // Para ajustar el pequeño desfase de redondeo (ej. 100 / 3 = 33.33 -> falta 0.01)
                    // Si es la última cuota, ajustamos los centavos para que cuadre exacto con el total
                    $montoFinalCuota = $montoCuota;
                    if ($i === (int) $request->cantidad_cuotas) {
                        $montoAcumulado = $montoCuota * ($request->cantidad_cuotas - 1);
                        $montoFinalCuota = $venta->total - $montoAcumulado;
                    }

                    // Creamos la cuota individual en la base de datos
                    Cuota::create([
                        'id_plan_pago' => $plan->id,
                        'numero_cuota' => $i,
                        'fecha_vencimiento' => $fechaVencimiento,
                        'monto' => $montoFinalCuota,
                        'estado_cuota' => 'Pendiente',
                        'state' => 'a'
                    ]);
                }
            });

            return redirect()->route('plan_pagos.index')->with('success', 'Plan de pago y cuotas generadas exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el plan: ' . $e->getMessage());
        }
    }





    /**
     * Muestra los detalles de un plan específico y sus cuotas.
     */
    public function show($id)
    {
        $plan = PlanPago::with(['venta.cliente', 'cuotas' => function($query) {
            // Ordenamos las cuotas de la 1 a la N
            $query->orderBy('numero_cuota', 'asc');
        }])->findOrFail($id);

        return Inertia::render('PlanPagos/Show', [
            'plan' => $plan
        ]);
    }



}