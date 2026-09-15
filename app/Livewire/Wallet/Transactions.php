<?php

namespace App\Livewire\Wallet;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Transaction History')]
class Transactions extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $wallet = Auth::user()->wallet;

        $query = $wallet->transactions()->with('approver')->latest();

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        return view('livewire.wallet.transactions', [
            'transactions' => $query->paginate(15),
            'totalDeposits' => $wallet->totalDeposits(),
            'totalWithdrawals' => $wallet->totalWithdrawals(),
            'totalProfit' => $wallet->totalProfit(),
        ]);
    }
}
