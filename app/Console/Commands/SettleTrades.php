<?php

namespace App\Console\Commands;

use App\Models\Trade;
use App\Models\WalletTransaction;
use App\Services\PriceService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SettleTrades extends Command
{
    protected $signature = 'trades:settle';

    protected $description = 'Settle expired open trades';

    public function handle(PriceService $prices): void
    {
        $trades = Trade::where('status', 'open')->where('expires_at', '<=', now())->get();

        $settled = 0;
        $voided = 0;

        foreach ($trades as $trade) {
            try {
                $exitPrice = $prices->currentPrice($trade->asset);
            } catch (ModelNotFoundException $e) {
                // The trade's asset no longer maps to an active market (e.g. a
                // discontinued/legacy symbol), so there's no real price to judge
                // win or loss against. Void it and refund the stake — it's the
                // platform that can't evaluate the trade, not something the
                // trader lost. Wrapped in a transaction so a failure partway
                // through (e.g. a rejected enum value) can't leave the trade
                // marked voided without the wallet actually being credited —
                // exactly what happened here once already before this fix.
                DB::transaction(function () use ($trade) {
                    $refund = (float) $trade->stake;

                    $trade->update([
                        'status' => 'voided',
                        'settled_at' => now(),
                    ]);

                    WalletTransaction::create([
                        'wallet_id' => $trade->wallet_id,
                        'type' => 'trade_void_refund',
                        'amount' => $refund,
                        'status' => 'completed',
                        'reference_type' => Trade::class,
                        'reference_id' => $trade->id,
                        'note' => "Trade voided — no active market for \"{$trade->asset}\"",
                    ]);

                    $trade->wallet->increment('balance', $refund);
                });

                $this->warn("Voided trade #{$trade->id}: no active market for asset \"{$trade->asset}\" — stake refunded.");
                $voided++;

                continue;
            }

            $won = $trade->direction === 'rise'
                ? $exitPrice > $trade->entry_price
                : $exitPrice < $trade->entry_price;

            $payout = $won ? round($trade->stake * 1.85, 2) : null;

            DB::transaction(function () use ($trade, $exitPrice, $won, $payout) {
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
            });

            $settled++;
        }

        $this->info("Settled {$settled} trade(s), voided {$voided}.");
    }
}
