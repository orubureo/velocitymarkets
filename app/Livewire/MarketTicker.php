<?php

namespace App\Livewire;

use App\Services\PriceService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MarketTicker extends Component
{
    /** Majors shown in the ticker (both the TradingView widget and the fallback). */
    const SYMBOLS = ['BTCUSDT', 'ETHUSDT', 'BNBUSDT', 'XRPUSDT', 'SOLUSDT', 'DOGEUSDT', 'ADAUSDT', 'LINKUSDT', 'AVAXUSDT', 'DOTUSDT'];

    public function render(PriceService $prices): View
    {
        return view('livewire.market-ticker', [
            // Only used if the TradingView widget fails to load (e.g. blocked by
            // an ad-blocker) — real prices, not fabricated, same source as the
            // rest of the app's live price data.
            'fallbackMarkets' => $prices->tickerMarkets(self::SYMBOLS),
        ]);
    }
}
