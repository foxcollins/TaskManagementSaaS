<div>
    <!-- Botón para abrir el modal -->
    <div class="btn-group" role="group" aria-label="Basic example">
        <button @click="$dispatch('openTaskCreateModal')" type="button" class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
    </div>
    <!-- Modal para Crear Tarea -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Crear Nueva Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveTask">
                        <div class="mb-3">
                            <label for="title" class="form-label">Nombre de la Tarea</label>
                            <input type="text" wire:model.live="title" class="form-control" id="title" required>
                                <ul class="suggestions-list" wire:loading.remove>
                                    @if (!empty($suggestions))
                                        @foreach ($suggestions as $suggestion)
                                            <li wire:click="selectSuggestion('{{ addslashes($suggestion) }}')" style="cursor: pointer;">
                                                {{ $suggestion }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            <div>
                                @error('title') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea wire:model.live="description" class="form-control" id="description" required></textarea>
                            <ul class="suggestions-list" wire:loading.remove>
                                    @if (!empty($suggestionsDescription))
                                        @foreach ($suggestionsDescription as $suggestionDes)
                                            <li wire:click="selectSuggestionDescription('{{ addslashes($suggestionDes) }}')" style="cursor: pointer;">
                                                {{ $suggestionDes }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            <div>
                                @error('description') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="$dispatch('saveTask')" type="submit" class="btn btn-success" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
    .suggestions-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
    }
        .suggestions-list li {
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd; /* Borde para confirmar que existen */
        display: block;
        color: #000;
    }
    .suggestions-list li:hover {
        background-color: #f0f0f0;
    }
</style>
</div>

<!-- Script para abrir y cerrar el modal usando eventos Livewire -->
@script
<script>
    
    $wire.on('show-create-modal', () => {
        const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
        taskModal.show();
    });
    $wire.on('hide-create-modal', () => {
        const taskModal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
        taskModal.hide();
    });
    $wire.on('task-limit', () => {
        Swal.fire({
            icon: "error",
            title: "Límite de tareas superado",
            text: "Tu cuenta ha alcanzado el máximo permitido de tareas. Para crear nuevas, elimina algunas existentes o actualiza tu plan.",
            footer: '<a href="{{route("plans")}}">Ver Planes</a>'
        });
    });
  
</script>
<script>
    function selectSuggestion(suggestion) {
        @this.set('title', suggestion); // Completar el título en Livewire
        suggestions = []; // Limpiar las sugerencias
    }

    function selectSuggestionDescription(suggestion) {
        @this.set('description', suggestion); // Completar el título en Livewire
        suggestionsDescription = []; // Limpiar las sugerencias
    }
</script>
@endscript


