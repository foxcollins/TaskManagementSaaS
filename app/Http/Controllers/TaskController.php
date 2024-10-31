<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 


class TaskController extends BaseController
{

    use AuthorizesRequests;

    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return $this->sendResponse($tasks, 'Tasks successfully ', 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            // Verifica si el usuario ha alcanzado el límite de tareas
            if ($user->hasReachedTaskLimit()) {
                return response()->json([
                    'success' => false,
                    'taskLimit'=>true,
                    'message' => 'Has alcanzado el límite de tareas para tu plan.'
                ], 403);
            }
            
            //Validación de datos
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Crear la tarea
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description ?? null,
                'priority'  => $request->priority ?? "low",
                'status' => Task::STATUS_PENDING,
                'user_id' => $user->id,
            ]);

            // Respuesta exitosa
            return $this->sendResponse($task, 'Task created successfully.', 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return $this->sendError('Task creation failed.', ['error' => $e->getMessage()], 500);
        }
    }



    public function show(Task $task)
    {
        try {
            // Verifica si el usuario autenticado es el dueño de la tarea
            if (Auth::id() !== $task->user_id) {
                return $this->sendError('You are not authorized to view this task.', [], 403);
            }

            // Si todo está bien, retorna la tarea con éxito
            return $this->sendResponse($task, 'Task retrieved successfully.', 200);
        } catch (\Exception $e) {
            // Manejo de errores en caso de que ocurra una excepción
            return $this->sendError('Failed to retrieve task.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            
            $task = Task::find($id);
            if (!$task) {
                return $this->sendError('Task not found.', [], 404);
            }
            //$this->authorize('update', $task);
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            // Actualizar la tarea con los datos validados
            $task->update($request->only('title', 'description', 'order', 'status'));
            Log::info(['TaskController - Update'=>$task]);
            return $this->sendResponse($task, 'Task updated successfully.', 201);
        } catch (Exception $e) {
            Log::info(['TasksController - UpdateTask'=>json_encode($e)]);
            return $this->sendError('Task update failed.', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();
            return $this->sendResponse([], 'Task deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Task could not be deleted.', ['error' => $e->getMessage()], 500);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {

            $task = Task::find($id);
            if (!$task) {
                return $this->sendError('Task not found.', [], 404);
            }
            //$this->authorize('update', $task);
            $validator = Validator::make($request->all(), [
                'status' => 'string',
                'order' =>'integer'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            // Actualizar la tarea con los datos validados
            $task->update($request->only('status','order'));
            Log::info(['TaskController - Update' => $task]);
            return $this->sendResponse($task, 'Task updated successfully.', 200);
        } catch (Exception $e) {

            return $this->sendError('Task update failed.', ['error' => $e->getMessage()], 500);
        }
    }
}
