<?php

namespace App\Livewire\Signal;

use App\Models\SignalTier;
use App\Models\UserSignal;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Buy Signal')]
class Buy extends Component
{
    public ?int $selectedTierId = null;

    public bool $showBuyModal = false;

    public function openBuyModal(int $tierId): void
    {
        $this->selectedTierId = $tierId;
        $this->showBuyModal = true;
    }

    public function closeBuyModal(): void
    {
        $this->showBuyModal = false;
        $this->reset(['selectedTierId']);
    }

    public function buy(): void
    {
        $userId = Auth::id();
        $tierId = $this->selectedTierId;

        $result = DB::transaction(function () use ($userId, $tierId) {
            $tier = SignalTier::where('is_active', true)->findOrFail($tierId);

            // Locked so a double-submit (or two concurrent requests) can't both
            // read the same pre-debit balance and each debit against it.
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->firstOrFail();

            $amount = round((float) $wallet->balance * $tier->percent / 100, 2);

            if ($amount <= 0) {
                return null;
            }

            $signal = UserSignal::create([
                'user_id' => $userId,
                'wallet_id' => $wallet->id,
                'signal_tier_id' => $tier->id,
                'percent' => $tier->percent,
                'amount' => $amount,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($tier->duration_days),
            ]);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'signal_purchase',
                'amount' => -$amount,
                'status' => 'completed',
                'reference_type' => UserSignal::class,
                'reference_id' => $signal->id,
                'note' => "Bought {$tier->name} ({$tier->percent}% signal)",
            ]);

            $wallet->decrement('balance', $amount);

            return [$tier->name, $amount];
        });

        if ($result === null) {
            Flux::toast(variant: 'danger', text: 'Your wallet balance is too low to buy this signal.');

            return;
        }

        [$tierName, $amount] = $result;
        session()->flash('status', "You've bought {$tierName} for \$".number_format($amount, 2).'.');

        $this->closeBuyModal();
    }

    public function render(): View
    {
        return view('livewire.signal.buy', [
            'tiers' => SignalTier::where('is_active', true)->orderBy('sort_order')->get(),
            'mySignals' => UserSignal::where('user_id', Auth::id())->with('tier')->latest()->get(),
            'selectedTier' => $this->selectedTierId ? SignalTier::where('is_active', true)->find($this->selectedTierId) : null,
            'balance' => Auth::guard('web')->user()->wallet->balance,
        ]);
    }
}
