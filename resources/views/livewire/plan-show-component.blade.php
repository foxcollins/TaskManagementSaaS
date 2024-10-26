<div class="container-fluid">
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <p class="fs-5 text-muted">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It’s built with default Bootstrap components and utilities with little customization.</p>
    </div>
    <div class="d-flex justify-content-center mt-2">
        
        @if ($plan)
            <div class="col-lg-6 text-center">
                <div class="card rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        
                    </div>
                    <div class="card-body p-5">
                        <h4 class="display-4 my-0 fw-normal">Plan {{$plan['name']}}</h4>
                          
                        <h1 class="card-title pricing-card-title">${{$plan['price']}} <small class="text-muted fw-light">/
                            @if ($plan['duration'] === 30)
                                Mes
                            @elseif ($plan['duration'] === 365)
                                Anual
                            @else
                                {{ $plan['duration'] }} días
                            @endif
                        </small></h1>
                        
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>{{$plan['description']}}</li>
                            <li>{{$plan['task_limit']}} Tareas maximo</li>
                            @if ($plan['duration'] === 30)
                                <p>Pago Mensual</p>
                            @elseif ($plan['duration'] === 365)
                                <p>Pago Anual</p>
                            @else
                                <p>pago cada {{ $plan['duration'] }} días</p> <!-- Para duraciones personalizadas -->
                            @endif
                        </ul>

                        @if($plan['id'] === $user['currentSubscription']['plan_id'])
                            <button type="button" class="w-100 btn btn-lg btn-outline-primary disabled">Plan Actual</button>
                        @else
                            <button wire:click="confirmPlan" class=" btn btn-lg btn-outline-primary">Confirmar plan</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    
    </div>
</div>
