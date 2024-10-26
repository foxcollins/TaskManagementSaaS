<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient();
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function createPayment($data)
    {
        $response = $this->provider->createOrder($data);

        if (isset($response['status']) && $response['status'] === 'CREATED') {
            return [
                'success' => true,
                'data' => $response,
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? 'Error al crear el pago',
        ];
    }

    public function executePayment($orderId)
    {
        $response = $this->provider->capturePaymentOrder($orderId);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            return [
                'success' => true,
                'data' => $response,
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? 'Error al capturar el pago',
        ];
    }

    // Método adicional para obtener el estado de una orden
    public function getOrderStatus($orderId)
    {
        $response = $this->provider->showOrderDetails($orderId);

        return $response; // Puedes agregar lógica de manejo de errores aquí
    }

    // Método adicional para reembolsos
    public function refundPayment($saleId, $amount)
    {
        $response = $this->provider->refundPayment($saleId, $amount);

        return $response; // Manejar la respuesta según sea necesario
    }
}

