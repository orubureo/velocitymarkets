<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use App\Models\Trade;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User Details')]
#[Layout('layouts.admin')]
class UserShow extends Component
{
    private const SETTLED_STATUSES = ['approved', 'completed'];

    public User $user;

    public bool $showAdjustModal = false;

    public string $adjustType = 'credit';

    public string $adjustAmount = '';

    public string $adjustNote = '';

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function openAdjustModal(string $type): void
    {
        $this->adjustType = $type;
        $this->adjustAmount = '';
        $this->adjustNote = '';
        $this->showAdjustModal = true;
    }

    public function closeAdjustModal(): void
    {
        $this->showAdjustModal = false;
        $this->reset(['adjustAmount', 'adjustNote']);
        $this->adjustType = 'credit';
    }

    public function adjustBalance(): void
    {
        $wallet = $this->user->wallet;

        $this->validate([
            'adjustAmount' => $this->adjustType === 'credit'
                ? ['required', 'numeric', 'min:0.01']
                : ['required', 'numeric', 'min:0.01', 'max:'.$wallet->balance],
            'adjustNote' => ['required', 'string', 'max:255'],
        ], [
            'adjustAmount.max' => 'Debit amount exceeds the user\'s wallet balance.',
        ]);

        $amount = (float) $this->adjustAmount;
        $signedAmount = $this->adjustType === 'credit' ? $amount : -$amount;

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'admin_adjustment',
            'amount' => $signedAmount,
            'status' => 'completed',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'note' => $this->adjustNote,
        ]);

        $wallet->increment('balance', $signedAmount);
        $this->user->unsetRelation('wallet');

        $this->closeAdjustModal();
    }

    public function render(): View
    {
        $this->user->loadMissing('wallet', 'referrer');

        $wallet = $this->user->wallet;

        $totalDeposits = (float) WalletTransaction::where('wallet_id', $wallet?->id)
            ->where('type', 'deposit')
            ->whereIn('status', self::SETTLED_STATUSES)
            ->sum('amount');

        $totalWithdrawals = abs((float) WalletTransaction::where('wallet_id', $wallet?->id)
            ->where('type', 'withdrawal')
            ->whereIn('status', self::SETTLED_STATUSES)
            ->sum('amount'));

        return view('livewire.admin.user-show', [
            'wallet' => $wallet,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
            'tradesCount' => Trade::where('user_id', $this->user->id)->count(),
            'openTickets' => SupportTicket::where('user_id', $this->user->id)
                ->whereIn('status', ['open', 'in_progress'])->count(),
        ]);
    }
}
