<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-around">
                <div class="">
                    <b>Creadas {{$tasks->count()}} de {{session('userData')['currentSubscription']['task_limit']}} <small>Tareas</small></b>
                </div>
                <div class="col-8 d-flex justify-content-end">
                    @livewire('task-create-component')
                </div>
            </div>
            
        </div>
        <div x-data="{ initSortable() {
            const columns = ['pending', 'in_progress', 'completed'];
                columns.forEach(status => {
                    const el = document.getElementById(`tasks-${status}`);
                    Sortable.create(el, {
                        group: 'tasks',
                        animation: 150,
                        onEnd: (event) => {
                            const taskId = event.item.getAttribute('data-id');
                            const newStatus = event.to.getAttribute('data-status');
                            const newOrder = event.newIndex;
                            @this.call('moveTask', taskId, newStatus, newOrder);
                        },
                    });
                });
            }}" x-init="initSortable">
            <div class="row d-flex justify-content-between">
                <div class="row">
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <h3 class="text-xl font-bold">Pendiente</h3>
                        <div id="tasks-pending" data-status="pending" class="p-3 h-100">
                            @foreach ($pendingTasks as $task)
                                <div data-id="{{ $task['id'] }}" class="row mb-1">
                                    <div class="col-12 card task-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h5 class="card-title lh-sm">{{$task['title']}}</h5>
                                                </div>
                                                <div class="col-3">
                                                    <div class="d-flex justify-content-end">
                                                        <div class="">
                                                            <button class="btn-link text-decoration-none border-0 bg-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis fa-lg text-dark"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="#" wire:click.prevent="openEditModal({{ $task['id'] }})">Editar</a></li>
                                                                <li><a class="dropdown-item" href="#" wire:click.prevent="confirmDelete({{ $task['id'] }})">Eliminar</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-card-description px-2">
                                                <p class="card-text lh-sm lh-1">{{$task['description']}}</p>
                                            </div>
                                            <div class="row ">
                                                <p class="lh-sm text-muted fst-italic text-micro mt-3"><i class="fa-solid fa-calendar"></i> Creada: {{\Carbon\Carbon::parse($task['created_at'])->toFormattedDateString()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <h3 class="text-xl font-bold">En Proceso</h3>
                        <div id="tasks-in_progress" data-status="in_progress" class="p-3 h-100">
                            @foreach ($inProgressTasks as $task)
                                <div data-id="{{ $task['id'] }}" class="row mb-1">
                                    <div class="col-12 card task-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h5 class="card-title">{{$task['title']}}</h5>
                                                </div>
                                                <div class="col-3">
                                                    <div class="d-flex justify-content-end">
                                                        <div class="">
                                                            <button class="btn-link text-decoration-none border-0 bg-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis fa-lg text-dark"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="#" wire:click.prevent="openEditModal({{ $task['id'] }})">Editar</a></li>
                                                                <li><a class="dropdown-item" href="#" wire:click.prevent="confirmDelete({{ $task['id'] }})">Eliminar</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-card-description px-2">
                                                <p class="card-text lh-sm lh-1">{{$task['description']}}</p>
                                            </div>
                                            <div class="row ">
                                                <p class="lh-sm text-muted fst-italic text-micro mt-3"><i class="fa-solid fa-calendar"></i> Creada: {{\Carbon\Carbon::parse($task['created_at'])->toFormattedDateString()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-sm-12 col-xs-12">
                        <h3 class="text-xl font-bold">Completado</h3>
                        <div id="tasks-completed" data-status="completed" class="p-3 h-100">
                            @foreach ($completedTasks as $task)
                                <div data-id="{{ $task['id'] }}" class="row mb-1">
                                    <div class="col-12 card task-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h5 class="card-title">{{$task['title']}}</h5>
                                                </div>
                                                <div class="col-3">
                                                    <div class="d-flex justify-content-end">
                                                        <div class="">
                                                            <button class="btn-link text-decoration-none border-0 bg-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis fa-lg text-dark"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="#" wire:click.prevent="openEditModal({{ $task['id'] }})">Editar</a></li>
                                                                <li><a class="dropdown-item" href="#" wire:click.prevent="confirmDelete({{ $task['id'] }})">Eliminar</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-card-description px-2">
                                                <p class="card-text lh-sm lh-1">{{$task['description']}}</p>
                                            </div>
                                            <div class="row ">
                                                <p class="lh-sm text-muted fst-italic text-micro mt-3"><i class="fa-solid fa-calendar"></i> Creada: {{\Carbon\Carbon::parse($task['created_at'])->toFormattedDateString()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Modal de edición -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar tarea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @livewire('task-edit-component',['taskId'=>$selectedTaskId])
                </div>
            </div>
        </div>

        <!-- Modal de eliminación -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que quieres eliminar esta tarea?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteTask">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const taskCards = document.querySelectorAll('.task-card');

            taskCards.forEach(card => {
                card.addEventListener('click', function () {
                    // Verificar si la tarjeta está ya expandida
                    const isExpanded = card.classList.contains('expanded');

                    // Cerrar todas las tarjetas
                    taskCards.forEach(c => c.classList.remove('expanded'));

                    // Si no estaba expandida, expandir la tarjeta actual
                    if (!isExpanded) {
                        card.classList.add('expanded');
                    }
                });
            });
        });
    </script>
@endpush

@script
<script>
    $wire.on('taskCreated', () => {
        Swal.fire({
            title: "Success",
            text: "la tarea fue creada con exito",
            icon: "success"
        });
    });

    $wire.on('openEditModal', () => {
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    });

    $wire.on('openDeleteModal', () => {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });

    $wire.on('taskDeleted', () => {
        var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        if (deleteModal) {
            deleteModal.hide();
        }
        Swal.fire({
            title: "Task Eliminada",
            text: "la tarea fue eliminada con exito",
            icon: "success"
        });
    });
    $wire.on('hide-edit-modal', () => {
        const taskModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        taskModal.hide();
        Swal.fire({
            title: "Task Actualizada",
            text: "la tarea fue actualizada con exito",
            icon: "success"
        });
    });

</script>
@endscript
