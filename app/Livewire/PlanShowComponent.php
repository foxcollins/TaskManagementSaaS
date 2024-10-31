<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PlanShowComponent extends Component
{
    public $plan;
    public $user;

    public function mount($plan)
    {
        try {
            $this->user = session('userData');
            $token = session('token');
            $client = new Client();
            // Hacer la solicitud POST a la API de registro
            $response = $client->get(env('APP_URL').'/api/subscription-plans/'.$plan.'', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token, // Asegúrate de incluir el token de autenticación
                ],
                'verify' => false, // Desactiva la verificación SSL para entornos locales; actívala en producción
            ]);
            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                $this->plan = $responseBody['data'];
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                $errorMessage = $errorResponse['message'] ?? 'Login failed';
                session()->flash('error', $errorMessage);

                // Verifica si hay errores de validación específicos en la respuesta de la API
                if (isset($errorResponse['data'])) {
                    foreach ($errorResponse['data'] as $field => $message) {
                        session()->flash('error', $message);
                    }
                }
            } else {
                session()->flash('error', 'Login failed: Server did not respond.');
            }
        } catch (\Exception $e) {
            // Manejar otros errores
            session()->flash('error', 'Login  failed: ' . $e->getMessage());
        }
    }

    public function confirmPlan($plan_id)
    {
        try {
            $client = new Client(); // Instancia de Guzzle
            // Realiza la solicitud POST hacia la API usando Guzzle
            $response = $client->post(env('APP_URL').'/api/subscriptions/subscribe', [
                'json' => [
                    'plan_id' => $plan_id,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'), // Reemplaza con tu token
                    'Accept' => 'application/json',
                ],
                'verify' => false
            ]);
            
            // Decodifica la respuesta JSON
            $data = json_decode($response->getBody(), true);

            if ($data['success']) {
                // Redirige a la URL de aprobación
                return redirect()->to($data['data']['approval_url']);
            } else {
                session()->flash('error', $data['message'] ?? 'Failed to create subscription.');
            }
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error', 'There was an error creating the subscription.');
        }
    }
    public function render()
    {
        return view('livewire.plan-show-component');
    }
}
