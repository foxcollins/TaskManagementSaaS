<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionPlanController extends BaseController
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return $this->sendResponse($plans, 'Subscription plans retrieved successfully.');
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'task_limit'=>'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Crear un nuevo plan de suscripción
        $plan = SubscriptionPlan::create($request->all());
        return $this->sendResponse($plan, 'Subscription plan created successfully.', 201);
    }

    public function show($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return $this->sendError('Subscription plan not found.', [], 404);
        }
        return $this->sendResponse($plan, 'Subscription plan retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'task_limit' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Actualizar el plan de suscripción
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return $this->sendError('Subscription plan not found.', [], 404);
        }

        $plan->update($request->all());
        return $this->sendResponse($plan, 'Subscription plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return $this->sendError('Subscription plan not found.', [], 404);
        }

        $plan->delete();
        return $this->sendResponse([], 'Subscription plan deleted successfully.');
    }
}
