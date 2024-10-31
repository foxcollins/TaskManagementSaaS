<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SubscriptionCompleted extends Component
{
    public $user;

    public function mount(){
        try {
            // Hacer la solicitud GET a la API de usuario
            if(!session('completed')){
                return redirect('/home');
            }
            $client = new Client();
            $token = session('token');

            $response = $client->get('https://taskmanagement.mn/api/user-data', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token, // Asegúrate de incluir el token de autenticación
                ],
                'verify' => false, // Desactiva la verificación SSL para entornos locales; actívala en producción
            ]);

            // Verifica el código de estado de la respuesta
            if ($response->getStatusCode() === 200) {
                $responseBody = json_decode($response->getBody()->getContents(), true);

                // Aquí puedes trabajar con la respuesta

                $this->user['id'] = $responseBody['data']['id'];
                $this->user['name'] = $responseBody['data']['name'];
                $this->user['email'] = $responseBody['data']['email'];
                $this->user['currentSubscription'] = $responseBody['data']['currentSubscription'];
                session()->put('userData', $this->user);
            } else {
                // Manejo de códigos de estado no exitosos
                $errorResponse = json_decode($response->getBody()->getContents(), true);
                session()->flash('error', 'Error fetching user data: ' . ($errorResponse['message'] ?? 'Unknown error'));
            }
        } catch (RequestException $e) {
            // Manejo de excepciones para errores de solicitud
            session()->flash('error', 'Request failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Manejo de excepciones generales
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.subscription-completed',['userData'=>$this->user])
        ->layout('layouts.app');
    }
}
