<?php

namespace App\Livewire\Trade;

use App\Models\UserInvestment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Portfolio')]
class Portfolio extends Component
{
    public string $tab = 'active';

    public function render(): View
    {
        $wallet = Auth::guard('web')->user()->wallet;

        $activeInvestments = UserInvestment::where('user_id', Auth::id())->where('status', 'active')->with('plan')->latest()->get();
        $completedInvestments = UserInvestment::where('user_id', Auth::id())->where('status', 'completed')->with('plan')->latest()->get();

        $allInvestments = $activeInvestments->concat($completedInvestments);

        return view('livewire.trade.portfolio', [
            'activeInvestments' => $activeInvestments,
            'completedInvestments' => $completedInvestments,
            'totalValue' => $wallet->balance + $activeInvestments->sum('amount'),
            'totalInvested' => $allInvestments->sum('amount'),
            'totalPnl' => $wallet->totalProfit(),
            'holdingsCount' => $activeInvestments->count(),
        ]);
    }
}
