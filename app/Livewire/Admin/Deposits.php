<?php

namespace App\Livewire\Admin;

use App\Models\WalletTransaction;
use App\Notifications\WalletTransactionUpdated;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Deposits')]
#[Layout('layouts.admin')]
class Deposits extends Component
{
    public function approve(int $transactionId)
    {
        $transaction = WalletTransaction::findOrFail($transactionId);

        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ]);

        $transaction->wallet->increment('balance', $transaction->amount);

        $this->creditReferralBonus($transaction);

        $transaction->wallet->user->notify(new WalletTransactionUpdated($transaction));
    }

    protected function creditReferralBonus(WalletTransaction $transaction): void
    {
        $user = $transaction->wallet->user;

        if (! $user->referred_by || $user->referral_bonus_paid) {
            return;
        }

        $bonusAmount = round($transaction->amount * 0.05, 2); // 5% — placeholder, adjust to your actual rate
        $referrerWallet = $user->referrer->wallet;

        WalletTransaction::create([
            'wallet_id' => $referrerWallet->id,
            'type' => 'referral_bonus',
            'amount' => $bonusAmount,
            'status' => 'completed',
            'note' => "Referral bonus — {$user->name}'s first deposit",
        ]);

        $referrerWallet->increment('balance', $bonusAmount);
        $user->update(['referral_bonus_paid' => true]);
    }

    public function reject(int $transactionId)
    {
        $transaction = WalletTransaction::findOrFail($transactionId);

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ]);

        $transaction->wallet->user->notify(new WalletTransactionUpdated($transaction));
    }

    public function render()
    {
        return view('livewire.admin.deposits', [
            'deposits' => WalletTransaction::where('type', 'deposit')
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }
}
