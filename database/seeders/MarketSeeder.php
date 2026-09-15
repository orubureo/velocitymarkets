<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(base_path('crypto_pairs.json'));
        $pairs = json_decode($json, true);

        $this->command->info('Seeding '.count($pairs).' markets...');

        foreach ($pairs as $index => $pair) {
            Market::updateOrCreate(
                ['symbol' => $pair['symbol']],
                [
                    'display_name' => $pair['display_name'],
                    'coingecko_id' => $pair['coingecko_id'],
                    'tradingview_symbol' => $pair['tradingview_symbol'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }

        $this->command->info('Markets seeded successfully!');
    }
}
