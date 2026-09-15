<?php

namespace App\Livewire\CopyTrading;

use App\Models\CopyTradeSubscription;
use App\Models\Trader;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Copy Trading')]
class Traders extends Component
{
    public ?int $selectedTraderId = null;

    public string $amount = '';

    public bool $showCopyModal = false;

    public string $search = '';

    public string $riskFilter = 'all';

    public function openCopyModal(int $traderId)
    {
        $this->selectedTraderId = $traderId;
        $this->amount = '';
        $this->showCopyModal = true;
    }

    public function closeCopyModal()
    {
        $this->showCopyModal = false;
        $this->reset(['selectedTraderId', 'amount']);
    }

    public function copy()
    {
        $trader = Trader::findOrFail($this->selectedTraderId);
        $wallet = Auth::user()->wallet;

        $maxAllowed = $trader->max_copy_amount
            ? min((float) $trader->max_copy_amount, (float) $wallet->balance)
            : (float) $wallet->balance;

        $this->validate([
            'amount' => ['required', 'numeric', 'min:'.$trader->min_copy_amount, 'max:'.$maxAllowed],
        ], [
            'amount.max' => 'Amount exceeds the trader maximum or your available balance.',
        ]);

        $subscription = CopyTradeSubscription::create([
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'trader_id' => $trader->id,
            'amount' => $this->amount,
            'status' => 'active',
            'started_at' => now(),
        ]);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'copy_trade_allocation',
            'amount' => -$this->amount,
            'status' => 'completed',
            'reference_type' => CopyTradeSubscription::class,
            'reference_id' => $subscription->id,
            'note' => "Started copying {$trader->name}",
        ]);

        $wallet->decrement('balance', $this->amount);

        session()->flash('status', "You're now copying {$trader->name}.");

        $this->closeCopyModal();
    }

    public function render()
    {
        $active = CopyTradeSubscription::where('user_id', Auth::id())->where('status', 'active')->get();

        return view('livewire.copy-trading.traders', [
            'traders' => Trader::where('is_active', true)
                ->when($this->search, function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })
                ->when($this->riskFilter !== 'all', function ($q) {
                    $q->where('risk_level', $this->riskFilter);
                })
                ->withCount(['subscriptions' => fn ($q) => $q->where('status', 'active')])
                ->orderBy('sort_order')
                ->get(),
            'activeCount' => $active->count(),
            'totalAllocated' => $active->sum('amount'),
            'netPnl' => $active->sum(fn ($sub) => $sub->netPnl()),
            'selectedTrader' => $this->selectedTraderId ? Trader::find($this->selectedTraderId) : null,
            'balance' => Auth::user()->wallet->balance,
        ]);
    }
}
