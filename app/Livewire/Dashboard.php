<?php

namespace App\Livewire;

use App\Models\Trader;
use App\Models\UserInvestment;
use App\Services\PriceService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render(PriceService $prices): View
    {
        $user = Auth::guard('web')->user();
        $wallet = $user->wallet;

        return view('livewire.dashboard', [
            'marketOverview' => array_slice($prices->tickerMarkets(), 0, 6),
            'accountBalance' => $wallet->balance,
            'totalProfit' => $wallet->totalProfit(),
            'referralBonus' => $wallet->totalReferralBonus(),
            'totalWithdrawal' => $wallet->totalWithdrawals(),
            'referralLink' => url('/register?ref='.$user->referral_code),
            'totalReferrals' => $user->referrals()->count(),
            'recentTransactions' => $wallet->transactions()->latest()->take(5)->get(),
            'activeInvestments' => UserInvestment::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with('plan')
                ->latest()
                ->take(3)
                ->get(),
            'featuredTraders' => Trader::where('is_active', true)
                ->withCount(['subscriptions' => fn ($q) => $q->where('status', 'active')])
                ->orderByDesc('roi_30d')
                ->take(3)
                ->get(),
        ]);
    }
}
