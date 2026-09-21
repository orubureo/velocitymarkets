<?php

namespace App\Livewire;

use App\Services\PriceService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MarketTicker extends Component
{
    /** Majors shown in the ticker. */
    const SYMBOLS = ['BTCUSDT', 'ETHUSDT', 'BNBUSDT', 'XRPUSDT', 'SOLUSDT', 'DOGEUSDT', 'ADAUSDT', 'LINKUSDT', 'AVAXUSDT', 'DOTUSDT'];

    public function render(PriceService $prices): View
    {
        return view('livewire.market-ticker', [
            // Real prices, not fabricated — same source as the rest of the app's live price data.
            'markets' => $prices->tickerMarkets(self::SYMBOLS),
        ]);
    }
}
