<?php

namespace App\Http\Controllers;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseController
{
    public function index()
    {
        $subscriptions = UserSubscription::where('user_id', Auth::id())->get();
        return $this->sendResponse($subscriptions, 'Subscriptions retrieved successfully.');
    }
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $subscription = UserSubscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $request->plan_id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(), // Cambia según la duración del plan
        ]);

        return $this->sendResponse($subscription, 'Subscribed successfully.', 201);
    }
}
