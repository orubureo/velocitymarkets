<?php

namespace Database\Seeders;

use App\Models\AccountTier;
use Illuminate\Database\Seeder;

class AccountTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            ['name' => 'Classic', 'price' => 500, 'daily_profit_percent' => 1.5, 'total_return_percent' => 45, 'referral_bonus_percent' => 5],
            ['name' => 'Standard', 'price' => 2000, 'daily_profit_percent' => 2.5, 'total_return_percent' => 150, 'referral_bonus_percent' => 8],
            ['name' => 'Premium', 'price' => 5000, 'daily_profit_percent' => 4, 'total_return_percent' => 300, 'referral_bonus_percent' => 12],
            ['name' => 'VIP', 'price' => 10000, 'daily_profit_percent' => 5, 'total_return_percent' => 450, 'referral_bonus_percent' => 15],
        ];

        foreach ($tiers as $index => $tier) {
            AccountTier::updateOrCreate(
                ['name' => $tier['name']],
                [...$tier, 'description' => 'Upgrade your account level', 'is_active' => true, 'sort_order' => $index]
            );
        }
    }
}
