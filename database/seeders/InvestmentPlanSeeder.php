<?php

namespace Database\Seeders;

use App\Models\InvestmentPlan;
use Illuminate\Database\Seeder;

class InvestmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Starter Plan', 'min_amount' => 100, 'max_amount' => 999, 'roi_percent' => 15, 'duration_days' => 15],
            ['name' => 'Growth Plan', 'min_amount' => 1000, 'max_amount' => 4999, 'roi_percent' => 35, 'duration_days' => 30],
            ['name' => 'Premium Plan', 'min_amount' => 5000, 'max_amount' => 19999, 'roi_percent' => 60, 'duration_days' => 45],
            ['name' => 'Elite Plan', 'min_amount' => 20000, 'max_amount' => 100000, 'roi_percent' => 120, 'duration_days' => 60],
        ];

        foreach ($plans as $index => $plan) {
            InvestmentPlan::updateOrCreate(
                ['name' => $plan['name']],
                [...$plan, 'is_active' => true, 'sort_order' => $index]
            );
        }
    }
}
