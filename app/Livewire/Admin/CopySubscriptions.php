<?php

namespace App\Livewire\Admin;

use App\Models\CopyTradeSubscription;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Copy Subscriptions')]
#[Layout('layouts.admin')]
class CopySubscriptions extends Component
{
    public bool $showCreditModal = false;

    public ?int $creditingSubscriptionId = null;

    public string $creditAmount = '';

    public string $creditType = 'profit';

    public function openCreditModal(int $subscriptionId, string $type)
    {
        $this->creditingSubscriptionId = $subscriptionId;
        $this->creditType = $type;
        $this->creditAmount = '';
        $this->showCreditModal = true;
    }

    public function closeCreditModal()
    {
        $this->showCreditModal = false;
        $this->reset(['creditingSubscriptionId', 'creditAmount']);
        $this->creditType = 'profit';
    }

    public function creditPnl()
    {
        $this->validate([
            'creditAmount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $subscription = CopyTradeSubscription::with('trader')->findOrFail($this->creditingSubscriptionId);

        $type = $this->creditType === 'profit' ? 'copy_trade_profit' : 'copy_trade_loss';
        $signedAmount = $this->creditType === 'profit' ? $this->creditAmount : -$this->creditAmount;

        WalletTransaction::create([
            'wallet_id' => $subscription->wallet_id,
            'type' => $type,
            'amount' => $signedAmount,
            'status' => 'completed',
            'reference_type' => CopyTradeSubscription::class,
            'reference_id' => $subscription->id,
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'note' => ucfirst($this->creditType)." from copying {$subscription->trader->name}",
        ]);

        $subscription->wallet->increment('balance', $signedAmount);

        $this->closeCreditModal();
    }

    public function stopSubscription(int $subscriptionId)
    {
        CopyTradeSubscription::findOrFail($subscriptionId)->update([
            'status' => 'stopped',
            'stopped_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.copy-subscriptions', [
            'subscriptions' => CopyTradeSubscription::where('status', 'active')->with(['user', 'trader'])->latest()->get(),
        ]);
    }
}
