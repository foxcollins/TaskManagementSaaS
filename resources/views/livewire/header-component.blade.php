<div>
    <header class="p-3 bg-primary bg-gradient text-white">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/home" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
          <img src="{{asset('images/logo.png')}}" alt="" width="100">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ms-md-5">
          <li><a href="{{route('home')}}" class="nav-link px-2 text-white"><i class="fa-solid fa-list-check"></i> Mis Tasks</a></li>
          <li><a href="{{route('plans')}}" class="nav-link px-2 text-white"><i class="fa-solid fa-scale-unbalanced"></i> Planes</a></li>
          <li><a href="{{route('about-us')}}" class="nav-link px-2 text-white"><i class="fa-solid fa-circle-info"></i> Acerca De</a></li>
        </ul>
        <span class="mx-4">Plan activo: {{$user['currentSubscription']['plan_name']}}</span>
        <div class="text-end">
            <div class="flex-shrink-0 dropdown">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                  @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && isset($user['profile_photo_url']) )
                    <img src="{{ $user['profile_photo_url'] }}"  alt="{{ $user['name'] }}" width="32" height="32" class="rounded-circle object-cover">
                  @else
                    <img src="https://i.pinimg.com/736x/dc/9c/61/dc9c614e3007080a5aff36aebb949474.jpg"  alt="{{ $user['name'] }}" width="32" height="32" class="rounded-circle object-cover">
                  @endif
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li class="text-center"><h6 class="dropdown-header">¡Hola, <span class="text-capitalice">{{$user['name']}}</span>!</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{route('payments')}}"><i class="fa-solid fa-clock-rotate-left"></i> Payment History</a></li>
                    <li><a class="dropdown-item" href="{{route('profile.show')}}"><i class="fa-solid fa-id-badge"></i> Account</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{route('logout')}}"><i class="fa-solid fa-right-from-bracket"></i> Salir</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </header>
</div>
