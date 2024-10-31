<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TaskList extends Component
{
    public $tasks = [];
    public $pendingTasks = [];
    public $inProgressTasks = [];
    public $completedTasks = [];
    public $selectedTaskId;

    protected $listeners = [
        'taskCreated' => 'refreshTasks',
        'taskUpdated' => 'refreshTasks',
        'taskDeleted' => 'refreshTasks',
    ];

    public function mount()
    {
        $this->loadTasks();
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

    public function loadTasks()
    {
        try {
            $response = $this->apiClient()->get('tasks');
            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                $this->tasks = collect($responseBody['data']); // Convertir directamente a colección
                
                $this->categorizeTasks();
            } else {
                session()->flash('error', 'Error al cargar las tareas.');
            }
        } catch (\Exception $e) {
            Log::error('TaskList component - LoadTask ERROR', ['exception' => $e]);
            session()->flash('error', 'Error al cargar las tareas.');
        }
    }


    private function categorizeTasks()
    {
        // Ordenar las tareas por el campo 'order'
        $sortedTasks = $this->tasks->sortBy('order');

        // Filtrar las tareas por estado usando métodos de la colección
        $this->pendingTasks = $sortedTasks->filter(fn($task) => $task['status'] === 'pending')->values()->toArray();
        $this->inProgressTasks = $sortedTasks->filter(fn($task) => $task['status'] === 'in_progress')->values()->toArray();
        $this->completedTasks = $sortedTasks->filter(fn($task) => $task['status'] === 'completed')->values()->toArray();
    }

    public function moveTask($taskId, $newStatus,$newOrder)
    {
        try {
            // Realiza la solicitud PATCH a la API
            $response = $this->apiClient()->patch("tasks/{$taskId}", [
                'json' => [
                    'status' => $newStatus,
                    'order' => $newOrder,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                // Carga todas las tareas actualizadas
                $this->loadTasks();
            } else {
                session()->flash('error', 'Error al mover la tarea.');
            }
        } catch (\Exception $e) {
            Log::error('TaskList component - moveTask ERROR', ['exception' => $e]);
            session()->flash('error', 'Error al mover la tarea.');
        }
    }

    public function refreshTasks()
    {
        $this->loadTasks();
    }

    public function openEditModal($taskId)
    {
        $this->selectedTaskId = $taskId;
        $this->dispatch('openEditModal');
        $this->dispatch('load-task',$this->selectedTaskId)->to(TaskEditComponent::class);
    }

    public function confirmDelete($taskId)
    {
        $this->selectedTaskId = $taskId;
        $this->dispatch('openDeleteModal');
    }

    public function deleteTask()
    {
        $this->apiClient()->delete("tasks/{$this->selectedTaskId}");
        $this->dispatch('taskDeleted');
        $this->refreshTasks();
    }

    public function render()
    {
        return view('livewire.task-list', [
            'pendingTasks' => $this->pendingTasks,
            'inProgressTasks' => $this->inProgressTasks,
            'completedTasks' => $this->completedTasks,
        ]);
    }
}
