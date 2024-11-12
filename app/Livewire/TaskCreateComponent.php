<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

class TaskCreateComponent extends Component
{
    #[Validate('required')] 
    public $title;
    #[Validate('required')] 
    public $description;
    public $suggestions;
    public $suggestionsDescription;

    protected $listeners = ['openTaskCreateModal' => 'showModal'];

    private function apiClient2()
    {
        return new Client([
            'base_uri' => env('APP_URL') . '/api/',
            'headers' => [
                'Authorization' => 'Bearer ' . session('token'),
            ],
            'verify' => false,
        ]);
    }
    public function showModal()
    {
        $this->reset();
        $this->dispatch('show-create-modal');
    }

    public function saveTask()
    {
        $this->validate(); 
        try {
            $response = $this->apiClient2()->post("tasks", [
                'json' => [
                    'title' => $this->title,
                    'description' => $this->description,
                ],
            ]);

            if ($response->getStatusCode() === 201) {
                $this->dispatch('taskCreated');
                $this->dispatch('hide-create-modal');
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Captura los errores de respuesta de la API
            $statusCode = $e->getResponse()->getStatusCode();
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            
            if ($statusCode === 403 && isset($responseBody['message'])) {
                if(isset($responseBody['taskLimit'])){
                    $this->dispatch('task-limit');
                }else{
                    session()->flash('error', $responseBody['message']); // Muestra el mensaje de error de la API
                    Log::info(['API ERROR' => $responseBody['message']]);
                    $this->dispatch('taskCreated');
                }
                
            } else {
                session()->flash('error', 'Error al crear la tarea.');
            }
            
            $this->dispatch('hide-create-modal');
        } catch (\Exception $e) {
            Log::info(['TaskCreate - saveTask'=>json_encode($e)]);
            session()->flash('error', 'Error al crear la tarea.');
        }
    }

    public function updatingTitle($value)
    {
        if (strlen($value) > 2) { // Cambia el límite según necesites
            $this->fetchSuggestions($value , 'title',null);
        } else {
            $this->suggestions = []; // Limpiar las sugerencias si el input es corto
        }
        
    }

    public function updatingDescription($value)
    {
        if (strlen($value) > 2) { // Cambia el límite según necesites
            $this->fetchSuggestions($value, 'description',$this->title);
        } else {
            $this->suggestionsDescription = []; // Limpiar las sugerencias si el input es corto
        }
    }

    public function fetchSuggestions($input,$type,$title=null)
    {
        try {
            if($title==null && $type=='title'){
                $response = $this->apiClient2()->post("suggest", [
                    'json' => [
                        'prompt' => $input,
                        'type' => $type
                    ],
                ]);
            }else{
                $response = $this->apiClient2()->post("suggest/desc", [
                    'json' => [
                        'prompt' => $input,
                        'type' => $type,
                        'title' => $title
                    ],
                ]);
            }
            
            if ($response->getStatusCode() === 200) {
                $responseBody = json_decode($response->getBody()->getContents(), true);
                if($type=='title'){
                    $this->suggestions = array_values(array_filter(array_map('trim', explode("\n", $responseBody['data'])), function ($line) {
                        return !empty($line) && strlen($line) > 3; // Filtrar líneas vacías o muy cortas
                    }));
                }elseif($type == "description"){
                    $this->suggestionsDescription = array_values(array_filter(array_map('trim', explode("\n", $responseBody['data'])), function ($line) {
                        return !empty($line) && strlen($line) > 3; // Filtrar líneas vacías o muy cortas
                    }));
                }
            } else {
                $this->suggestionsDescription = $this->suggestions = [];
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching suggestions', ['error' => $e]);
            $this->suggestions = [];
        }
    }

    public function selectSuggestion($suggestion)
    {
        $this->title = $suggestion;
        $this->suggestions = []; // Limpiar las sugerencias después de seleccionar
    }

    public function selectSuggestionDescription($suggestion)
    {
        $this->description = $suggestion;
        $this->suggestionsDescription = []; // Limpiar las sugerencias después de seleccionar
    }

    public function render()
    {
        return view('livewire.task-create-component',['suggestions'=>$this->suggestions]);
    }
}