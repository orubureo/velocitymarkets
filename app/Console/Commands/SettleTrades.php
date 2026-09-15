<?php

namespace App\Console\Commands;

use App\Models\Trade;
use App\Models\WalletTransaction;
use App\Services\PriceService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettleTrades extends Command
{
    protected $signature = 'trades:settle';

    protected $description = 'Settle expired open trades';

    public function handle(PriceService $prices): void
    {
        $trades = Trade::where('status', 'open')->where('expires_at', '<=', now())->get();

        $settled = 0;

        foreach ($trades as $trade) {
            try {
                $exitPrice = $prices->currentPrice($trade->asset);
            } catch (ModelNotFoundException $e) {
                $this->warn("Skipping trade #{$trade->id}: no active market for asset \"{$trade->asset}\".");

                continue;
            }

            $won = $trade->direction === 'rise'
                ? $exitPrice > $trade->entry_price
                : $exitPrice < $trade->entry_price;

            $payout = $won ? round($trade->stake * 1.85, 2) : null;

            $trade->update([
                'exit_price' => $exitPrice,
                'status' => $won ? 'won' : 'lost',
                'payout' => $payout,
                'settled_at' => now(),
            ]);

            if ($won) {
                WalletTransaction::create([
                    'wallet_id' => $trade->wallet_id,
                    'type' => 'trade_profit',
                    'amount' => $payout,
                    'status' => 'completed',
                    'reference_type' => Trade::class,
                    'reference_id' => $trade->id,
                    'note' => "Trade won — {$trade->asset} ".ucfirst($trade->direction),
                ]);

                $trade->wallet->increment('balance', $payout);
            }

            $settled++;
        }

        $this->info("Settled {$settled} trade(s).");
    }
}
