<div>
    <header class="p-3 bg-primary bg-gradient text-white">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
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
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li class="text-center"><h6 class="dropdown-header">¡Hola, <span class="text-capitalice">{{$user['name']}}</span>!</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-clock-rotate-left"></i> Historial</a></li>
                    <li><a class="dropdown-item" href="{{route('profile.show')}}"><i class="fa-solid fa-id-badge"></i> Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket"></i> Salir</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </header>
</div>
