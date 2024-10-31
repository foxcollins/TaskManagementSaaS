<div>
    <form wire:submit.prevent="updateTask">
        <div class="modal-body">
            <div class="mb-3">
                <label for="title" class="form-label">Nombre de la Tarea</label>
                <input type="text" wire:model="utitle" class="form-control" id="title" required>
                <div>
                    @error('title') <span class="error">{{ $message }}</span> @enderror 
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea wire:model="udescription" class="form-control" id="description" required></textarea>
                <div>
                    @error('description') <span class="error">{{ $message }}</span> @enderror 
                </div>
            </div> 
        </div>
        <div class="modal-footer">
            <button wire:click="$dispatch('updateTask')" type="submit" class="btn btn-success" >Actualizar</button>
        </div>
    </form>
     
</div>



