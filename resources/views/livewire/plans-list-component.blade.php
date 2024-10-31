<div class="container-fluid">
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-4 fw-normal">Planes</h1>
      <p class="fs-5 text-muted">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It’s built with default Bootstrap components and utilities with little customization.</p>
    </div>
    <div class="d-flex justify-content-center mt-5">
        <div class="col-12 col-lg-8">
            <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                @if ($plans)
                    @foreach ($plans as $plan)
                        <div class="col">
                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">{{$plan['name']}}</h4>
                                </div>
                                <div class="card-body">
                                    @if ($plan['price']==0)
                                        <h1 class="card-title pricing-card-title">Free</small></h1>
                                    @else   
                                        <h1 class="card-title pricing-card-title">${{$plan['price']}} <small class="text-muted fw-light">/mo</small></h1>
                                    @endif
                                    
                                    <ul class="list-unstyled mt-3 mb-4">
                                        <li>{{$plan['description']}}</li>
                                        <li>{{$plan['task_limit']}} Tareas</li>
                                    </ul>
            
                                    @if($plan['id'] === $user['currentSubscription']['plan_id'])
                                        <button type="button" class="w-100 btn btn-lg btn-outline-primary disabled">Plan Actual</button>
                                    @else
                                        <a href="{{route('plans.show',$plan['id'])}}" class="w-100 btn btn-lg btn-outline-primary">Seleccionar plan</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

