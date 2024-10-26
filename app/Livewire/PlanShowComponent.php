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
            $response = $client->get('https://taskmanagement.mn/api/subscription-plans/'.$plan.'', [
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

    public function confirmPlan()
    {
        // Lógica para confirmar la suscripción al plan
        // Podrías llamar a una API o un método para actualizar la suscripción del usuario
        
        session()->flash('success', 'Plan confirmado exitosamente.');
        return redirect()->route('home'); // Redirigir a donde sea necesario
    }
    public function render()
    {
        return view('livewire.plan-show-component');
    }
}
