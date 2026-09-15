<?php

namespace Database\Seeders;

use App\Models\Trader;
use Illuminate\Database\Seeder;

class TraderSeeder extends Seeder
{
    public function run(): void
    {
        $traders = [
            ['name' => 'Anna Kowalski', 'avatar_initials' => 'AK', 'tagline' => 'Forex Master', 'bio' => 'Decade-long forex veteran running leveraged macro positions across major currency pairs, chasing outsized moves during high-volatility sessions.', 'tier' => 'elite', 'risk_level' => 'high', 'win_rate' => 84.10, 'roi_30d' => 201.30, 'base_copiers' => 2847, 'min_copy_amount' => 200, 'max_copy_amount' => 20000],
            ['name' => 'Dmitri Volkov', 'avatar_initials' => 'DV', 'tagline' => 'Scalper', 'bio' => 'High-frequency scalper firing dozens of short-term trades a day, targeting small, rapid price inefficiencies across major pairs.', 'tier' => 'elite', 'risk_level' => 'high', 'win_rate' => 52.40, 'roi_30d' => 187.90, 'base_copiers' => 1203, 'min_copy_amount' => 100, 'max_copy_amount' => 15000],
            ['name' => 'Natalie Brooks', 'avatar_initials' => 'NB', 'tagline' => 'Options Strategist', 'bio' => 'Derivatives specialist structuring options spreads to capture premium while keeping downside exposure contained.', 'tier' => 'elite', 'risk_level' => 'medium', 'win_rate' => 61.30, 'roi_30d' => 94.80, 'base_copiers' => 1564, 'min_copy_amount' => 150, 'max_copy_amount' => 12000],
            ['name' => 'Kenji Watanabe', 'avatar_initials' => 'KW', 'tagline' => 'Quantitative', 'bio' => 'Systematic quant running backtested, rules-based models designed to compound steadily with tightly controlled drawdowns.', 'tier' => 'elite', 'risk_level' => 'low', 'win_rate' => 85.60, 'roi_30d' => 41.20, 'base_copiers' => 2105, 'min_copy_amount' => 100, 'max_copy_amount' => 15000],
            ['name' => 'Isabella Rossi', 'avatar_initials' => 'IR', 'tagline' => 'DeFi Specialist', 'bio' => 'DeFi-native trader rotating capital across emerging protocols and liquidity pools ahead of the broader market.', 'tier' => 'pro', 'risk_level' => 'high', 'win_rate' => 47.80, 'roi_30d' => 256.30, 'base_copiers' => 1689, 'min_copy_amount' => 200, 'max_copy_amount' => 25000],
            ['name' => 'James Miller', 'avatar_initials' => 'JM', 'tagline' => 'Crypto Top 1%', 'bio' => 'Full-time crypto trader riding momentum across major and mid-cap tokens, sized for aggressive upside.', 'tier' => 'pro', 'risk_level' => 'high', 'win_rate' => 78.40, 'roi_30d' => 142.60, 'base_copiers' => 3121, 'min_copy_amount' => 100, 'max_copy_amount' => 10000],
            ['name' => 'Carlos Mendes', 'avatar_initials' => 'CM', 'tagline' => 'Macro Trader', 'bio' => 'Macro-focused trader positioning around interest-rate decisions, economic data releases, and cross-asset trends.', 'tier' => 'pro', 'risk_level' => 'medium', 'win_rate' => 68.90, 'roi_30d' => 76.40, 'base_copiers' => 847, 'min_copy_amount' => 75, 'max_copy_amount' => 8000],
            ['name' => 'Omar Haddad', 'avatar_initials' => 'OH', 'tagline' => 'Trend Following', 'bio' => 'Trend follower riding sustained directional moves and stepping aside once momentum fades.', 'tier' => 'pro', 'risk_level' => 'medium', 'win_rate' => 58.70, 'roi_30d' => 65.20, 'base_copiers' => 612, 'min_copy_amount' => 50, 'max_copy_amount' => 7000],
            ['name' => 'Lena Fischer', 'avatar_initials' => 'LF', 'tagline' => 'Arbitrage Specialist', 'bio' => 'Arbitrage trader capturing small, low-risk price differences across exchanges and trading pairs.', 'tier' => 'pro', 'risk_level' => 'low', 'win_rate' => 91.20, 'roi_30d' => 18.70, 'base_copiers' => 1342, 'min_copy_amount' => 25, 'max_copy_amount' => 4000],
            ['name' => 'Ryan Scott', 'avatar_initials' => 'RS', 'tagline' => 'Stocks & Indices', 'bio' => 'Blends equity index exposure with crypto correlations, balancing growth with measured risk.', 'tier' => 'verified', 'risk_level' => 'medium', 'win_rate' => 71.20, 'roi_30d' => 98.40, 'base_copiers' => 428, 'min_copy_amount' => 50, 'max_copy_amount' => 5000],
            ['name' => 'Priya Sharma', 'avatar_initials' => 'PS', 'tagline' => 'Swing Trader', 'bio' => 'Swing trader holding positions for days to weeks, favoring confirmed setups over frequent trading.', 'tier' => 'verified', 'risk_level' => 'low', 'win_rate' => 79.40, 'roi_30d' => 33.50, 'base_copiers' => 356, 'min_copy_amount' => 50, 'max_copy_amount' => 5000],
            ['name' => 'David Chen', 'avatar_initials' => 'DC', 'tagline' => 'Long-term Hold', 'bio' => 'Long-term holder accumulating through pullbacks and staying the course through short-term volatility.', 'tier' => 'verified', 'risk_level' => 'low', 'win_rate' => 88.50, 'roi_30d' => 24.30, 'base_copiers' => 291, 'min_copy_amount' => 25, 'max_copy_amount' => 3000],
        ];

        foreach ($traders as $index => $trader) {
            Trader::updateOrCreate(
                ['name' => $trader['name']],
                [...$trader, 'is_active' => true, 'sort_order' => $index]
            );
        }
    }
}
