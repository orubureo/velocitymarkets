<?php

namespace App\Livewire\Admin;

use App\Models\Market;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Markets')]
#[Layout('layouts.admin')]
class Markets extends Component
{
    public string $symbol = '';
    public string $displayName = '';
    public string $coingeckoId = '';
    public string $tradingviewSymbol = '';

    public function addMarket()
    {
        $this->validate([
            'symbol' => ['required', 'string', 'max:20'],
            'displayName' => ['required', 'string', 'max:20'],
            'coingeckoId' => ['required', 'string', 'max:50'],
            'tradingviewSymbol' => ['required', 'string', 'max:50'],
        ]);

        Market::create([
            'symbol' => strtoupper($this->symbol),
            'display_name' => $this->displayName,
            'coingecko_id' => strtolower($this->coingeckoId),
            'tradingview_symbol' => strtoupper($this->tradingviewSymbol),
            'sort_order' => Market::max('sort_order') + 1,
        ]);

        $this->reset(['symbol', 'displayName', 'coingeckoId', 'tradingviewSymbol']);
    }

    public function toggleActive(int $marketId)
    {
        $market = Market::findOrFail($marketId);
        $market->update(['is_active' => !$market->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.markets', [
            'markets' => Market::orderBy('sort_order')->get(),
        ]);
    }
}