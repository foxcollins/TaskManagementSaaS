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

    public function cancelSubscription(Request $request)
    {
        $subscription = UserSubscription::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->first();

        if ($subscription) {
            $subscription->update([
                'canceled_at' => now(),
                'is_active' => false,
                'previous_plan_id' => $subscription->plan_id, // Guarda el plan anterior
            ]);

            // Otras lógicas necesarias, como notificaciones
            return $this->sendResponse($subscription, 'Subscribed successfully.', 201);
        }

        return $this->sendResponse(['message' => 'No active subscription found.'], 404);
    }

    public function changePlan(Request $request, $newPlanId)
    {
        $subscription = UserSubscription::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->first();

        if ($subscription) {
            // Al cambiar de plan, puedes mantener el plan anterior
            $subscription->update([
                'previous_plan_id' => $subscription->plan_id,
                'plan_id' => $newPlanId,
                'starts_at' => now(), // Establecer nueva fecha de inicio si es necesario
                'ends_at' => now()->addDays(30), // O el plazo correspondiente según el nuevo plan
            ]);

            return $this->sendResponse($subscription, 'Subscribed successfully.', 200);
        }

        return $this->sendResponse(['message' => 'No active subscription found.'], 404);
    }
}
