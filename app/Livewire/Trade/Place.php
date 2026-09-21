<?php

namespace App\Livewire\Trade;

use App\Models\Trade;
use App\Models\WalletTransaction;
use App\Services\PriceService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Trade')]
class Place extends Component
{
    public string $asset;

    public string $stake = '';

    public int $expiryMinutes = 5;

    public string $positionsTab = 'active';

    public function mount(string $symbol, PriceService $prices): void
    {
        $symbol = strtoupper($symbol);

        abort_unless($prices->supportedMarkets()->contains('symbol', $symbol), 404);

        $this->asset = $symbol;
    }

    public function placeTrade(string $direction, PriceService $prices): void
    {
        $wallet = Auth::guard('web')->user()->wallet;

        $this->validate([
            'stake' => ['required', 'numeric', 'min:1', 'max:'.$wallet->balance],
            'expiryMinutes' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $stake = (float) $this->stake;
        $entryPrice = $prices->currentPrice($this->asset);

        $trade = Trade::create([
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'asset' => $this->asset,
            'direction' => $direction,
            'stake' => $stake,
            'entry_price' => $entryPrice,
            'status' => 'open',
            'expires_at' => now()->addMinutes($this->expiryMinutes),
        ]);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'trade_loss',
            'amount' => -$stake,
            'status' => 'completed',
            'reference_type' => Trade::class,
            'reference_id' => $trade->id,
            'note' => "Trade placed — {$this->asset} ".ucfirst($direction),
        ]);

        $wallet->decrement('balance', $stake);

        session()->flash('status', 'Trade placed.');

        $this->reset('stake');
    }

    public function render(PriceService $prices): View
    {
        $markets = $prices->supportedMarkets();
        $currentMarket = $markets->firstWhere('symbol', $this->asset);

        return view('livewire.trade.place', [
            'currentPrice' => $prices->currentPrice($this->asset),
            'markets' => $markets,
            'marketIcons' => $prices->marketIcons(),
            'tradingViewSymbol' => $currentMarket->tradingview_symbol ?? 'BINANCE:BTCUSDT',
            'openTrades' => Trade::where('user_id', Auth::id())->where('status', 'open')->latest()->get(),
            'closedTrades' => Trade::where('user_id', Auth::id())->whereIn('status', ['won', 'lost', 'voided'])->latest('settled_at')->take(15)->get(),
            'balance' => Auth::guard('web')->user()->wallet->balance,
        ]);
    }
}
