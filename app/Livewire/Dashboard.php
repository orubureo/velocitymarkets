<?php

namespace App\Livewire;

use App\Models\CopyTradeSubscription;
use App\Models\Trade;
use App\Models\UserInvestment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $wallet = Auth::user()->wallet;

        $activeCopySubscriptions = CopyTradeSubscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('trader')
            ->latest()
            ->get();

        $openTrades = Trade::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'accountBalance' => $wallet->balance,
            'totalProfit' => $wallet->totalProfit(),
            'referralBonus' => $wallet->totalReferralBonus(),
            'totalWithdrawal' => $wallet->totalWithdrawals(),
            'referralLink' => url('/register?ref='.Auth::user()->referral_code),
            'recentTransactions' => $wallet->transactions()->latest()->take(5)->get(),
            'activeInvestments' => UserInvestment::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with('plan')
                ->latest()
                ->take(3)
                ->get(),
            'activeCopySubscriptions' => $activeCopySubscriptions,
            'copyNetPnl' => $activeCopySubscriptions->sum(fn ($sub) => $sub->netPnl()),
            'openTrades' => $openTrades,
            'openTradesStake' => $openTrades->sum('stake'),
        ]);
    }
}
