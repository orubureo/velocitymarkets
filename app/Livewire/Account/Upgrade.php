<?php

namespace App\Livewire\Account;

use App\Models\AccountTier;
use App\Models\WalletTransaction;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Account Upgrade')]
class Upgrade extends Component
{
    public ?int $selectedTierId = null;

    public bool $showUpgradeModal = false;

    public function openUpgradeModal(int $tierId): void
    {
        $this->selectedTierId = $tierId;
        $this->showUpgradeModal = true;
    }

    public function closeUpgradeModal(): void
    {
        $this->showUpgradeModal = false;
        $this->reset(['selectedTierId']);
    }

    public function upgrade(): void
    {
        $tier = AccountTier::findOrFail($this->selectedTierId);
        $user = Auth::guard('web')->user();
        $wallet = $user->wallet;

        if ($user->account_tier_id === $tier->id) {
            $this->closeUpgradeModal();

            return;
        }

        if ((float) $wallet->balance < (float) $tier->price) {
            Flux::toast(variant: 'danger', text: 'Your wallet balance is too low for this plan.');

            return;
        }

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'account_upgrade',
            'amount' => -(float) $tier->price,
            'status' => 'completed',
            'reference_type' => AccountTier::class,
            'reference_id' => $tier->id,
            'note' => "Upgraded to {$tier->name}",
        ]);

        $wallet->decrement('balance', (float) $tier->price);

        $user->update([
            'account_tier_id' => $tier->id,
            'account_tier_purchased_at' => now(),
        ]);

        session()->flash('status', "You've upgraded to the {$tier->name} plan.");

        $this->closeUpgradeModal();
    }

    public function render(): View
    {
        $user = Auth::guard('web')->user();

        return view('livewire.account.upgrade', [
            'tiers' => AccountTier::where('is_active', true)->orderBy('sort_order')->get(),
            'currentTierId' => $user->account_tier_id,
            'currentTier' => $user->accountTier,
            'balance' => $user->wallet->balance,
            'selectedTier' => $this->selectedTierId ? AccountTier::find($this->selectedTierId) : null,
        ]);
    }
}
