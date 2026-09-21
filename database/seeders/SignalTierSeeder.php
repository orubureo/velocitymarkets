<?php

namespace Database\Seeders;

use App\Models\SignalTier;
use Illuminate\Database\Seeder;

class SignalTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            ['name' => 'Starter Signal', 'percent' => 25, 'win_rate_percent' => 65, 'roi_percent' => 20, 'duration_days' => 7],
            ['name' => 'Growth Signal', 'percent' => 50, 'win_rate_percent' => 72, 'roi_percent' => 45, 'duration_days' => 14],
            ['name' => 'Advanced Signal', 'percent' => 75, 'win_rate_percent' => 78, 'roi_percent' => 80, 'duration_days' => 21],
            ['name' => 'Max Signal', 'percent' => 100, 'win_rate_percent' => 85, 'roi_percent' => 120, 'duration_days' => 30],
        ];

        foreach ($tiers as $index => $tier) {
            SignalTier::updateOrCreate(
                ['name' => $tier['name']],
                [...$tier, 'description' => 'Signal allocation plan', 'is_active' => true, 'sort_order' => $index]
            );
        }
    }
}
