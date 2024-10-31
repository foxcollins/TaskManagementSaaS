<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'description' => 'Free plan with limited tasks',
                'price' => 0.00,
                'duration' => 30, // 30 días
                'currency' => 'USD',
                'task_limit' => 5, // Limite de tareas para usuarios free
            ],
            [
                'name' => 'Basic',
                'description' => 'Basic plan with limited features',
                'price' => 10,
                'duration' => 30, // 30 días
                'currency' => 'USD',
                'task_limit' => 50, // Límite de tareas para usuarios básicos
                'paypal_plan_id' => 'P-6380937539222452XM4OJAMY',
            ],
            [
                'name' => 'Pro',
                'description' => 'Pro plan with additional features',
                'price' => 40,
                'duration' => 30, // 30 días
                'currency' => 'USD',
                'task_limit' => 200, // Límite de tareas para usuarios Pro
                'paypal_plan_id'=> 'P-5WX76056P55192714M4OJA5A',
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Enterprise plan with all features',
                'price' => 120,
                'duration' => 365, // 1 año
                'currency' => 'USD',
                'task_limit' => 1000, // Límite de tareas para usuarios Enterprise
                'paypal_plan_id' => 'P-8GR2684655586822FM4OJBUI',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
