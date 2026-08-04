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
        try {
            // El servicio hace toda la validación y genera el QR
            $resultado = $this->pagoFacilService->generarQrParaCuota($cuota);

            // Guardamos el ID de transacción de PagoFácil en la cuota para futuras consultas
            $cuota->update([
                'pagofacil_transaction_id' => $resultado['transactionId'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'qrImage' => $resultado['qrBase64'] ?? null, // Base64 directo para mostrar en el `<img>`
                'transaction_id' => $resultado['transactionId'] ?? null
            ]);

        } catch (\Exception $e) {
            // Si el servicio falla (ej. credenciales malas), atrapamos el error aquí
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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