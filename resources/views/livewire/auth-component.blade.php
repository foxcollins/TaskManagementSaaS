<div>
    @if ($view == "register")     
      @livewire('RegisterComponent')
        <p class="mt-3 mb-0">Already have an acount?</p>
        <a  wire:click="changeView" class="btn btn-link text-decoration-none">Login</a>
    @else
        @livewire('LoginComponent')
        <p class="mt-3 mb-0">Don't have an acount?</p>
        <a  wire:click="changeView" class="btn btn-link text-decoration-none">Crear cuenta</a>
    @endif
</div>
