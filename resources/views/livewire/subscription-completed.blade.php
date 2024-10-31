
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4 text-center" style="max-width: 500px; width: 100%;">
        <div class="card-body">
            <!-- Success Icon -->
            <div class="mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="text-success bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M16 8a8 8 0 1 1-16 0 8 8 0 0 1 16 0zM6.354 10.354a.5.5 0 0 0 .708 0l4-4a.5.5 0 1 0-.708-.708L6.5 9.293 5.354 8.146a.5.5 0 1 0-.708.708l1.5 1.5z"/>
            </svg>
            </div>

            <!-- Success Message -->
            <h3 class="card-title">Subscription Successful!</h3>
            <p class="card-text mt-3 mb-4">
            Thank you for subscribing to <strong>{{ $user['currentSubscription']['plan_name'] }} Plan</strong>. Your subscription is now active.
            </p>

            <!-- Subscription Details -->
            <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Plan Name: <span><strong>{{ $user['currentSubscription']['plan_name'] }}</strong></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Price: <span><strong>{{ $user['currentSubscription']['price'] }} {{ $user['currentSubscription']['currency'] }}</strong></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Start Date: <span><strong>{{ \Carbon\Carbon::parse($user['currentSubscription']['starts_at'])->toFormattedDateString() }}</strong></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                End Date: <span><strong>{{ \Carbon\Carbon::parse($user['currentSubscription']['ends_at'])->toFormattedDateString() }}</strong></span>
            </li>
            </ul>

            <!-- Action Buttons -->
            <a href="/home" class="btn btn-primary mb-3 w-100">Volver al Inicio</a>
            <button class="btn btn-outline-secondary w-100" onclick="window.print()">Print Confirmation</button>
        </div>
    </div>
</div>

