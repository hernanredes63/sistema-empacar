<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PagoFacilService
{
    protected $apiUrl;
    protected $tokenService;
    protected $tokenSecret;

    public function __construct()
    {
        $this->apiUrl = env('PAGOFACIL_API_URL');
        $this->tokenService = env('PAGOFACIL_TOKEN_SERVICE');
        $this->tokenSecret = env('PAGOFACIL_TOKEN_SECRET');
    }

    /**
     * Paso 1: Autenticación con PagoFácil para obtener el Token JWT.
     */
    private function login()
    {
        $response = Http::withHeaders([
            'tcTokenService' => $this->tokenService,
            'tcTokenSecret' => $this->tokenSecret,
        ])->post("{$this->apiUrl}/login");

        $data = $response->json();

        if ($response->successful() && isset($data['values']['accessToken'])) {
            return $data['values']['accessToken'];
        }

        Log::error('PagoFacil Login Error: ' . $response->body());
        throw new Exception("Error al autenticar con PagoFácil: " . ($data['message'] ?? 'Desconocido'));
    }

    /**
     * Paso 2: Generar el QR para una Cuota específica.
     */
    public function generarQr($cuota, $cliente)
    {
        // 1. Obtenemos el Token de Acceso
        $token = $this->login();

        // 2. Preparamos el payload según la documentación de PagoFácil v2
        $payload = [
            "paymentMethod" => 34, // ID del método QR (generalmente 34 para QrMaster)
            "clientName" => $cliente->nombre,
            "documentType" => 1, // 1 = CI
            "documentId" => $cliente->documento ?? '0000000',
            "phoneNumber" => $cliente->telefono ?? '00000000',
            "email" => $cliente->correo ?? 'sin@correo.com',
            "paymentNumber" => "CUOTA-" . $cuota->id . "-" . time(), // ID único de transacción para tu empresa
            "amount" => (float) $cuota->monto,
            "currency" => 2, // 2 = BOB
            "clientCode" => (string) $cliente->id,
            "callbackUrl" => route('pagofacil.webhook'), // URL a la que PagoFácil notificará el éxito
            "orderDetail" => [
                [
                    "serial" => 1,
                    "product" => "Pago de Cuota Nro " . $cuota->numero_cuota,
                    "quantity" => 1,
                    "price" => (float) $cuota->monto,
                    "discount" => 0,
                    "total" => (float) $cuota->monto
                ]
            ]
        ];

        // 3. Hacemos la petición POST a /generate-qr
        $response = Http::withToken($token)
            ->post("{$this->apiUrl}/generate-qr", $payload);

        $data = $response->json();

        if ($response->successful() && isset($data['values']['qrBase64'])) {
            return [
                'success' => true,
                'qrImage' => $data['values']['qrBase64'],
                'transactionId' => $data['values']['transactionId']
            ];
        }

        Log::error('PagoFacil Generate QR Error: ' . $response->body());
        return [
            'success' => false,
            'message' => $data['message'] ?? 'Error al generar el QR.'
        ];
    }
}