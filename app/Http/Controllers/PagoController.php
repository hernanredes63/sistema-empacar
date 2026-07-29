<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Services\PagoFacilService;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    protected $pagoFacilService;

    // Inyectamos nuestro servicio de PagoFácil
    public function __construct(PagoFacilService $pagoFacilService)
    {
        $this->pagoFacilService = $pagoFacilService;
    }

    /**
     * Genera el QR para una cuota específica usando la API v2.
     */
    public function generarQrCuota(Cuota $cuota)
    {
        // Cargamos la relación para obtener los datos del cliente
        $cuota->load('planPago.venta.cliente');
        $cliente = $cuota->planPago->venta->cliente;

        // Llamamos al servicio
        $resultado = $this->pagoFacilService->generarQr($cuota, $cliente);

        if ($resultado['success']) {
            // Guardamos el ID de transacción de PagoFácil en la cuota para futuras consultas
            $cuota->update([
                'pagofacil_transaction_id' => $resultado['transactionId']
            ]);

            return response()->json([
                'success' => true,
                'qrImage' => $resultado['qrImage']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $resultado['message']
        ], 500);
    }

    /**
     * Webhook para recibir la notificación de pago exitoso (lo configuraremos luego).
     */
    public function webhook(Request $request)
    {
        // Aquí recibiremos el aviso automático de PagoFácil
        return response()->json(['success' => true]);
    }
}