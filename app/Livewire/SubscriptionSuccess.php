<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SubscriptionSuccess extends Component
{

    public $subscription_id;
    public $ba_token;
    public $token;
    public $plan;

    public function mount()
    {
        // Captura los datos de la URL
        $this->subscription_id = request()->get('subscription_id');
        $this->ba_token = request()->get('ba_token');
        $this->token = request()->get('token');

        // Enviar los datos a la API para confirmar la suscripción
        $this->confirmSubscription();
    }

    public function confirmSubscription()
    {
        try {
           
            $client = new Client();
            $token = session('token');
            $client = new Client();
            // Hacer la solicitud POST a la API de registro
            $response = $client->post(env('APP_URL'). '/api/subscriptions/success', [
                'json' => [
                    'subscription_id' => $this->subscription_id,
                    'ba_token' => $this->ba_token,
                    'token' => $this->token
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $token, // Asegúrate de incluir el token de autenticación
                ],
                'verify' => false, // Desactiva la verificación SSL para entornos locales; actívala en producción
            ]);
            
            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                $this->plan = $responseBody['data'];
            }

            // Verifica el estado de la respuesta
            if ($response->getStatusCode() === 200) {
                session()->flash('message', 'Subscription confirmed successfully!');
                session()->flash('completed',true);
                return redirect()->route('subscription.completed');
            } else {
                session()->flash('error', 'There was an issue confirming your subscription.');
            }
        } catch (\Exception $e) {
            Log::error('Error confirming subscription: ' . $e->getMessage());
            session()->flash('error', 'An unexpected error occurred.');
        }
    }

    public function render()
    {
        return view('livewire.subscription-success')
        ->layout('layouts.app');
    }
}
