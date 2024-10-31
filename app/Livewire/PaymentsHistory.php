<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PaymentsHistory extends Component
{
    public $payments;

    public function mount()
    {
        $this->loadPayments();
    }
    public function loadPayments()
    {
        try {
            $response = $this->apiClient()->get('payments/history');
            $responseBody = json_decode($response->getBody()->getContents(), true);
            if ($response->getStatusCode() === 200) {
                $this->payments = $responseBody['data']; // Convertir directamente a colección
            } else {
                session()->flash('error', 'Error al cargar las tareas.');
            }
        } catch (\Exception $e) {
            Log::error('PaymentsHistory - LoadPayments ERROR', ['exception' => $e]);
            session()->flash('error', 'Error al cargar las tareas.');
        }
    }
    private function apiClient()
    {
        return new Client([
            'base_uri' => env('APP_URL') . '/api/',
            'headers' => [
                'Authorization' => 'Bearer ' . session('token'),
            ],
            'verify' => false,
        ]);
    }

    public function render()
    {
        return view('livewire.payments-history',['payments'=>$this->payments]);
    }
}
