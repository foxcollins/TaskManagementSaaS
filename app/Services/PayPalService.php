<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;
class PayPalService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient();
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    // Método para crear una suscripción
    public function createSubscription($planId)
    {
        $data = [
            'plan_id' => $planId,
            'application_context' => [
                'brand_name' => env('APP_NAME'),
                'locale' => 'en-US',
                'user_action' => 'SUBSCRIBE_NOW',
                'return_url' => route('subscription.success'),
                'cancel_url' => route('subscription.cancel'),
            ],
        ];

        $response = $this->provider->createSubscription($data);

        if (isset($response['status']) && $response['status'] === 'APPROVAL_PENDING') {
            return [
                'success' => true,
                'data' => $response['links'][0]['href'], // URL de aprobación para redirigir al usuario
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? 'Error al crear la suscripción',
        ];
    }

    // Método para cancelar una suscripción
    public function cancelSubscription($subscriptionId, $reason)
    {
        Log::info(['PayPalService cancelSubscription() $subscriptionId' => $subscriptionId]);

        // Verificar el estado de la suscripción antes de cancelarla
        $check = $this->getSubscriptionStatus($subscriptionId);
        Log::info(['Paypal Service - CancelSubscription - Check subscription' => $check]);

        // Solo proceder si el estado es ACTIVO o SUSPENDIDO
        if ($check['status'] == "ACTIVE" || $check['status'] == "SUSPENDED") {
            // Intentar cancelar la suscripción
            $response = $this->provider->cancelSubscription($subscriptionId, $reason);

            // Después de cancelar, verifica el estado de la suscripción
            $checkNow = $this->getSubscriptionStatus($subscriptionId);
            Log::info(['Paypal Service - After Cancel - Check subscription' => $checkNow]);

            // Verificar si el estado ha cambiado a CANCELLED
            if (is_array($checkNow) && $checkNow['status'] === 'CANCELLED') {
                return ['success' => true, 'message' => 'Suscripción cancelada exitosamente.'];
            }
        }

        // Manejo de error si no se pudo cancelar la suscripción
        return [
            'success' => false,
            'message' => is_array($response) ? ($response['message'] ?? 'Error al cancelar la suscripción') : 'Error en la respuesta de PayPal'
        ];
    }



    // Método para obtener el estado de la suscripción
    public function getSubscriptionStatus($subscriptionId)
    {
        return $this->provider->showSubscriptionDetails($subscriptionId);
    }
}

