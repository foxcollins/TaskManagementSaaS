<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LoginComponent extends Component
{
    public $loginEmail;
    public $loginPassword;
    public $algo;

    protected $rules = [
        'loginEmail' => 'required|email',
        'loginPassword' => 'required|min:6',
    ];

    public function mount(){
        // if (session('token')) {
        //     return redirect()->route('home');
        // }
    }

    public function login()
    {
        try {
            
            // Validar campos en el lado del cliente
            // $this->validate();
            // Crear cliente Guzzle
             $client = new Client();
            // Hacer la solicitud POST a la API de registro
            $response = $client->post('https://taskmanagement.mn/api/login-api', [
                'json' => [
                    'email' => $this->loginEmail,
                    'password' => $this->loginPassword,
                ],
                'verify' => false, // Desactiva la verificación SSL para entornos locales
            ]);
            $responseBody = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() === 200) {
                // Guardar el token en sesión y redirigir o mostrar éxito
                session()->flash('success', 'User loged successfully!');
                session()->put('token', explode('|', $responseBody['data']['token'])[1]); // Guarda solo el hash
                return redirect('/home');
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
    
    public function render()
    {
        return view('livewire.login-component');
    }
}
