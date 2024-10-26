<div>
    
        <img class="mb-4" src="{{asset('images/logo.png')}}" alt="" width="172">
        <h1 class="h3 mb-3 fw-normal">Please sign up</h1>

        
        <div class="form-floating mb-3">
            <input type="email" class="form-control" wire:model="loginEmail">
             @error('loginEmail') <span class="text-danger">{{ $message }}</span> @enderror
            <label for="loginEmail">Email address</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" wire:model="loginPassword" placeholder="Password" required>
            <label for="loginPassword">Password</label>
            @error('loginPassword') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button wire:click="login" class="w-100 btn btn-primary mb-2">Iniciar Sessión</button>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <p class="mt-3 mb-0">Don't have an acount?</p>
        <a href="{{route('register')}}" class="btn btn-link text-decoration-none">Crear cuenta</a>
</div>


