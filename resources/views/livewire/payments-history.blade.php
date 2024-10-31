<div class="container-fluid">
    <div class="col-md-12 text-center mt-4">
        <h4>Payments History</h4>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <div class="col-6">
            <div class="col-lg-12">
                <h5>Tu plan activo</h5>
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">Tu plan {{session('userData')['currentSubscription']['plan_name']}}</h4>
                    <hr>
                    <p class="mb-0">Tu proxima fecha de cobro es el <b>{{\Carbon\Carbon::parse(session('userData')['currentSubscription']['ends_at'])->toFormattedDateString()}}</b></p>
                </div>
            </div>
            <div class="col-lg-12 mt-4">
                <h5><i class="fa-solid fa-clock-rotate-left"></i> Historial de pagos </h5>
                @if ($payments)
                    <table class="table mt-4">
                    <thead>
                        <tr>
                        <th scope="col">Periodo</th>
                        <th scope="col">Plan</th>
                        <th scope="col">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <th scope="row"><small>{{\Carbon\Carbon::parse($payment['starts_at'])->toFormattedDateString()}} - {{\Carbon\Carbon::parse($payment['ends_at'])->toFormattedDateString()}}</small></th>
                                <td>{{$payment['plan']['name']}}</td>
                                <td>{{$payment['plan']['currency']}} {{$payment['plan']['price']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                @endif
            </div>
        </div>
    </div>
    
</div>
