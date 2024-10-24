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
                'price' => 9.99,
                'duration' => 30, // 30 días
                'currency' => 'USD',
                'task_limit' => 50, // Límite de tareas para usuarios básicos
            ],
            [
                'name' => 'Pro',
                'description' => 'Pro plan with additional features',
                'price' => 29.99,
                'duration' => 30, // 30 días
                'currency' => 'USD',
                'task_limit' => 200, // Límite de tareas para usuarios Pro
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Enterprise plan with all features',
                'price' => 99.99,
                'duration' => 365, // 1 año
                'currency' => 'USD',
                'task_limit' => 1000, // Límite de tareas para usuarios Enterprise
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
