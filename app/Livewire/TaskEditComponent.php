<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

class TaskEditComponent extends Component
{
    #[Validate('required')]
    public $utitle;
    #[Validate('required')]
    public $udescription;
    public $task;
    public $selectedTaskId;

    protected $listeners = [
        'load-task' => 'loadTask'
    ];

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

    public function loadTask($taskId)
    {
        try {
            $this->selectedTaskId = $taskId;
            $response = $this->apiClient()->get('tasks/'.$taskId);
            $responseBody = json_decode($response->getBody()->getContents(), true);
            if ($response->getStatusCode() === 200) {
                $this->task = $responseBody['data'];
                $this->utitle = $this->task['title'];
                $this->udescription = $this->task['description'];
            } else {
                session()->flash('error', 'Error al cargar la tarea.');
            }
        } catch (\Exception $e) {
            Log::error('TaskList component - LoadTask ERROR', ['exception' => $e]);
            session()->flash('error', 'Error al cargar la tarea.');
        }
    }

    public function updateTask()
    {
        $this->validate();
        try {
            $response = $this->apiClient()->put("tasks/{$this->selectedTaskId}", [
                'json' => [
                    'title' => $this->utitle,
                    'description' => $this->udescription
                ],
            ]);
            if ($response->getStatusCode() === 201) {
                Log::info(['TaskEditComponent - Response'=> $response]);
                $this->dispatch('taskUpdated')->to(TaskList::class);
                $this->dispatch('hide-edit-modal');
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Captura los errores de respuesta de la API
            $statusCode = $e->getResponse()->getStatusCode();
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);

            if ($statusCode === 403 && isset($responseBody['message'])) {
                session()->flash('error', $responseBody['message']); // Muestra el mensaje de error de la API
            } else {
                session()->flash('error', 'Error al crear la tarea.');
            }
            $this->dispatch('taskUpdated');
            $this->dispatch('hide-edit-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la tarea.');
        }
    }

    public function render()
    {
        return view('livewire.task-edit-component');
    }
}
