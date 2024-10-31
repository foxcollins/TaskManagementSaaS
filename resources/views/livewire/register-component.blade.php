<div>
    <form wire:submit.prevent="register">
        <img class="mb-4" src="{{asset('images/logo.png')}}" alt="" width="172">
        <h1 class="h3 mb-3 fw-normal">Please sign up</h1>

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

        <div class="form-floating mb-3">
            <input type="text" class="form-control" wire:model="name" placeholder="John Doe" required>
            <label for="name">Name</label>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" wire:model="email" placeholder="name@example.com" required>
            <label for="email">Email address</label>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" wire:model="password" placeholder="Password" required>
            <label for="password">Password</label>
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" wire:model="c_password" placeholder="Confirm Password" required>
            <label for="c_password">Confirm Password</label>
            @error('c_password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button class="w-100 btn btn-primary mb-2" type="submit">Register</button>
    </form>
        <p class="mt-3 mb-0">Already have an acount?</p>
        <a  href="{{route('login')}}" class="btn btn-link text-decoration-none">Login</a>
</div>


