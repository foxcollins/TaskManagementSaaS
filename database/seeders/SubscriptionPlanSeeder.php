<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Basic',
                'description' => 'Basic plan with limited features',
                'price' => 9.99,
                'duration' => 30, // 30 días
                'currency' => 'USD',
            ],
            [
                'name' => 'Pro',
                'description' => 'Pro plan with additional features',
                'price' => 29.99,
                'duration' => 30, // 30 días
                'currency' => 'USD',
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Enterprise plan with all features',
                'price' => 99.99,
                'duration' => 365, // 1 año
                'currency' => 'USD',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
