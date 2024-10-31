<?php

namespace App\Http\Controllers;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SubscriptionController extends BaseController
{
    protected $payPalService;

    public function __construct(PayPalService $payPalService)
    {
        $this->payPalService = $payPalService;
    }

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

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        if ($plan->paypal_plan_id) {
            return $this->subscribeToPayPalPlan($plan->paypal_plan_id);
        }

        $subscription = UserSubscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        return $this->sendResponse($subscription, 'Subscribed successfully.', 201);
    }

    public function subscribeToPayPalPlan($paypalPlanId)
    {
        $response = $this->payPalService->createSubscription($paypalPlanId);

        if ($response['success']) {
            return $this->sendResponse(['approval_url' => $response['data']], 'Subscription created. Redirect to PayPal for approval.', 200);
        }

        return $this->sendError('Failed to create PayPal subscription', $response['message'], 400);
    }

    public function cancelSubscription(Request $request)
    {
        $subscription = UserSubscription::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->first();

        if ($subscription) {
            if ($subscription->is_paypal) {
                return $this->cancelPayPalSubscription($subscription->paypal_subscription_id);
            }

            $subscription->update([
                'canceled_at' => now(),
                'is_active' => false,
                'previous_plan_id' => $subscription->plan_id,
            ]);

            return $this->sendResponse($subscription, 'Subscription canceled successfully.', 200);
        }

        return $this->sendResponse(['message' => 'No active subscription found.'], 404);
    }

    public function cancelPayPalSubscription($paypalSubscriptionId)
    {
        $response = $this->payPalService->cancelSubscription($paypalSubscriptionId);

        if ($response['success']) {
            return $this->sendResponse([], 'PayPal subscription canceled successfully.', 200);
        }

        return $this->sendError('Failed to cancel PayPal subscription', $response['message'], 400);
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'new_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $newPlanId = $request->new_plan_id;
        $currentSubscription = UserSubscription::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if ($currentSubscription && $currentSubscription->paypal_subscription_id) {
            $cancelResponse = $this->payPalService->cancelSubscription($currentSubscription->paypal_subscription_id);

            if (!$cancelResponse['success']) {
                return $this->sendError('Failed to cancel current subscription.', 500);
            }

            $newSubscription = UserSubscription::create([
                'user_id' => Auth::id(),
                'plan_id' => $newPlanId,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'is_active' => true,
                'paypal_plan_id' => $newPlanId,
            ]);

            return $this->sendResponse($newSubscription, 'Plan changed successfully.', 200);
        }

        return $this->sendError('No active subscription found or cancellation failed.', 404);
    }

    public function handleSuccess(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::id());
            $subscriptionId = $request->subscription_id;

            if (!$subscriptionId) {
                return $this->sendError('Subscription ID is missing.', 400);
            }

            $response = $this->payPalService->getSubscriptionStatus($subscriptionId);
            Log::info('SubscriptionController Response getSubscriptionStatus', ['response' => $response]);

            if (isset($response['status']) && $response['status'] === 'ACTIVE') {
                $newPlan = SubscriptionPlan::where('paypal_plan_id', $response['plan_id'])->first();

                $currentSubscription = UserSubscription::where('user_id', $user->id)
                    ->where('is_active', 1)
                    ->first();
                Log::info(['SubscriptionController - currentSubscription Local DB'=> $currentSubscription]);
                Log::info(['$currentSubscription->paypal_subscription_id'=>$currentSubscription['paypal_subscription_id']]);
                if ($currentSubscription && $currentSubscription['paypal_subscription_id']) {
                    $cancelResponse = $this->payPalService->cancelSubscription($currentSubscription['paypal_subscription_id'],'Cancelled by client on change plan');
                    Log::info('SubscriptionController cancelResponse cancelSubscription', ['cancelResponse' => $cancelResponse]);
                    if (!$cancelResponse['success']) {
                        return $this->sendError('Failed to cancel current subscription.', 500);
                    }
                }

                UserSubscription::create([
                    'plan_id' => $newPlan->id,
                    'user_id' => $user->id,
                    'paypal_subscription_id' => $subscriptionId,
                    'previous_plan_id' => $currentSubscription->id ?? null,
                    'paypal_plan_id'=>$newPlan->paypal_plan_id,
                    'is_active' => 1,
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth(),
                ]);

                if ($currentSubscription) {
                    $currentSubscription->update([
                        'is_active' => 0,
                        'ends_at' => now(),
                        'canceled_at' => $currentSubscription->canceled_at ?? now(),
                    ]);
                }

                return $this->sendResponse(null, 'Subscription confirmed and active.', 200);
            } else {
                return $this->sendError('Subscription is not active.', 400);
            }
        } catch (\Exception $e) {
            return $this->sendError('Error confirming subscription: ' . $e->getMessage(), 500);
        }
    }
}
