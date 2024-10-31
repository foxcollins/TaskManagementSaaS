<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RegisterComponent extends Component
{
    public $name;
    public $email;
    public $password;
    public $c_password;

    // Reglas de validación
    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'c_password' => 'required|same:password',
    ];

    public function mount()
    {
        if (session('token')) {
            return redirect()->route('home');
        }
    }

    public function register()
    {
        try {
            // Validar campos en el lado del cliente
            $this->validate();

            // Crear cliente Guzzle
            $client = new Client();
            // Hacer la solicitud POST a la API de registro
            $response = $client->post('https://taskmanagement.mn/api/register', [
                'json' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'c_password' => $this->c_password,
                ],
                'verify' => false, // Desactiva la verificación SSL para entornos locales
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                // Guardar el token en sesión y redirigir o mostrar éxito
                session()->flash('success', 'User registered successfully!');
                session()->put('token', explode('|', $responseBody['data']['token'])[1]); // Guarda solo el hash
                return redirect('/home');
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                $errorMessage = $errorResponse['message'] ?? 'Registration failed';
                session()->flash('error', $errorMessage);

                // Verifica si hay errores de validación específicos en la respuesta de la API
                if (isset($errorResponse['data'])) {
                    foreach ($errorResponse['data'] as $field => $message) {
                        session()->flash('error', $message[0]);
                    }
                }
            } else {
                session()->flash('error', 'Registration failed: Server did not respond.');
            }
        } catch (\Exception $e) {
            // Manejar otros errores
            session()->flash('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.register-component');
    }
}
