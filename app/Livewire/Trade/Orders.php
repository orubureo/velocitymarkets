<?php

namespace App\Livewire\Trade;

use App\Models\Trade;
use App\Services\PriceService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Orders')]
class Orders extends Component
{
    public string $tab = 'active';

    public function render(PriceService $prices): View
    {
        return view('livewire.trade.orders', [
            'activeTrades' => Trade::where('user_id', Auth::id())->where('status', 'open')->latest()->get(),
            'completedTrades' => Trade::where('user_id', Auth::id())->whereIn('status', ['won', 'lost', 'voided'])->latest('settled_at')->get(),
            'markets' => $prices->supportedMarkets(),
            'marketIcons' => $prices->marketIcons(),
        ]);
    }
}
