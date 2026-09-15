<?php

namespace App\Livewire\Admin;

use App\Models\WalletTransaction;
use App\Notifications\WalletTransactionUpdated;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Withdrawals')]
#[Layout('layouts.admin')]
class Withdrawals extends Component
{
    public function approve(int $transactionId)
    {
        $transaction = WalletTransaction::findOrFail($transactionId);
        $wallet = $transaction->wallet;

        // Re-check balance at approval time — guards against the case where
        // multiple pending withdrawals together exceed what's actually available now.
        if ($wallet->balance < abs($transaction->amount)) {
            session()->flash('error', 'Cannot approve — insufficient wallet balance.');
            return;
        }

        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ]);

        $wallet->increment('balance', $transaction->amount); // negative amount, so this subtracts

        $wallet->user->notify(new WalletTransactionUpdated($transaction));
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
        return view('livewire.admin.withdrawals', [
            'withdrawals' => WalletTransaction::where('type', 'withdrawal')
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }
}