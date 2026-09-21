<?php

namespace App\Livewire\Trade;

use App\Models\Trade;
use App\Models\Watchlist;
use App\Services\PriceService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Markets & Trading')]
class Markets extends Component
{
    public string $search = '';

    public bool $watchlistOnly = false;

    public function toggleWatchlist(string $symbol): void
    {
        $deleted = Watchlist::where('user_id', Auth::id())->where('symbol', $symbol)->delete();

        if (! $deleted) {
            Watchlist::create(['user_id' => Auth::id(), 'symbol' => $symbol]);
        }
    }

    public function render(PriceService $prices): View
    {
        $watchlisted = Watchlist::where('user_id', Auth::id())->pluck('symbol')->all();
        $search = strtolower($this->search);

        $markets = collect($prices->tickerMarkets())
            ->filter(function (array $market) use ($search, $watchlisted) {
                if ($search !== '' && ! str_contains(strtolower($market['display_name']), $search) && ! str_contains(strtolower($market['symbol']), $search)) {
                    return false;
                }

                if ($this->watchlistOnly && ! in_array($market['symbol'], $watchlisted, true)) {
                    return false;
                }

                return true;
            })
            ->values();

        $wallet = Auth::guard('web')->user()->wallet;

        $openTrades = Trade::where('user_id', Auth::id())->where('status', 'open')->get();

        $wonPnl = Trade::where('user_id', Auth::id())->where('status', 'won')->selectRaw('COALESCE(SUM(payout - stake), 0) as total')->value('total');
        $lostPnl = Trade::where('user_id', Auth::id())->where('status', 'lost')->selectRaw('COALESCE(SUM(stake), 0) as total')->value('total');

        return view('livewire.trade.markets', [
            'markets' => $markets,
            'watchlisted' => $watchlisted,
            'balance' => $wallet->balance,
            'activeStake' => $openTrades->sum('stake'),
            'openTradesCount' => $openTrades->count(),
            'totalPnl' => (float) $wonPnl - (float) $lostPnl,
        ]);
    }
}
