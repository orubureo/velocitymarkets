<?php

namespace App\Livewire\CopyTrading;

use App\Models\CopyTradeSubscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Subscriptions')]
class Subscriptions extends Component
{
    public function stopCopy(int $subscriptionId)
    {
        $subscription = CopyTradeSubscription::where('user_id', Auth::id())->findOrFail($subscriptionId);

        if (! $subscription->isActive()) {
            return;
        }

        $subscription->update([
            'status' => 'stopped',
            'stopped_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.copy-trading.subscriptions', [
            'mySubscriptions' => CopyTradeSubscription::where('user_id', Auth::id())->with('trader')->latest()->get(),
        ]);
    }
}
