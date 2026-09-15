<div wire:ignore
    x-data="{ tvFailed: false }"
    class="sticky top-16 lg:top-0 z-[5] shrink-0 w-full border-b border-zinc-200 dark:border-zinc-800 overflow-hidden"
>
    {{-- Primary: TradingView's classic ticker-tape embed — flat single-line
         items, no visible attribution line. The iframe is built by hand (not
         via TradingView's injected script) and reused across theme changes —
         destroying and recreating it on every toggle proved unreliable
         (TradingView's embed occasionally renders blank on re-injection), but
         reusing the same iframe and just changing its `src` is instant and
         reliable, so the ticker re-themes live without a page reload. --}}
    <div
        x-data="{
            iframe: null,
            buildUrl(dark) {
                const config = {
                    symbols: [
                        { proName: 'BINANCE:BTCUSDT', title: 'BTC/USDT' },
                        { proName: 'BINANCE:ETHUSDT', title: 'ETH/USDT' },
                        { proName: 'BINANCE:BNBUSDT', title: 'BNB/USDT' },
                        { proName: 'BINANCE:XRPUSDT', title: 'XRP/USDT' },
                        { proName: 'BINANCE:SOLUSDT', title: 'SOL/USDT' },
                        { proName: 'BINANCE:DOGEUSDT', title: 'DOGE/USDT' },
                        { proName: 'BINANCE:ADAUSDT', title: 'ADA/USDT' },
                        { proName: 'BINANCE:LINKUSDT', title: 'LINK/USDT' },
                        { proName: 'BINANCE:AVAXUSDT', title: 'AVAX/USDT' },
                        { proName: 'BINANCE:DOTUSDT', title: 'DOT/USDT' },
                    ],
                    showSymbolLogo: true,
                    isTransparent: false,
                    displayMode: 'regular',
                    colorTheme: dark ? 'dark' : 'light',
                    locale: 'en',
                    width: '100%',
                    height: 46,
                };
                return 'https://www.tradingview-widget.com/embed-widget/ticker-tape/?locale=en#' + encodeURIComponent(JSON.stringify(config));
            },
            retheme(dark) {
                if (!this.iframe) {
                    this.iframe = document.createElement('iframe');
                    this.iframe.style.width = '100%';
                    this.iframe.style.height = '46px';
                    this.iframe.style.border = 'none';
                    this.iframe.scrolling = 'no';
                    this.iframe.onerror = () => { tvFailed = true; };
                    this.$refs.host.appendChild(this.iframe);
                    setTimeout(() => { if (this.$refs.host.getBoundingClientRect().height < 5) tvFailed = true; }, 4000);
                }
                this.iframe.src = this.buildUrl(dark);
            },
        }"
        x-effect="retheme($flux.appearance === 'dark' || ($flux.appearance === 'system' && $flux.dark))"
        x-show="!tvFailed"
    >
        <div x-ref="host"></div>
    </div>

    {{-- Fallback: shown if TradingView's widget is blocked (ad-blockers commonly
         flag its domain) or fails to render within a few seconds. Real prices
         from the same PriceService the rest of the app uses, not fabricated. --}}
    @if (! empty($fallbackMarkets))
        <div x-show="tvFailed" x-cloak class="relative bg-white dark:bg-zinc-900">
            <div class="ticker-track py-3">
                @foreach ([...$fallbackMarkets, ...$fallbackMarkets] as $t)
                    <div class="flex items-center gap-2.5 shrink-0 px-5 border-r border-zinc-100 dark:border-zinc-800">
                        <x-crypto-icon :currency="preg_replace('/USDT$/', '', $t['symbol'])" :url="$t['image']" class="size-5 shrink-0" />
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white whitespace-nowrap">{{ $t['display_name'] }}</span>
                        <span class="text-sm font-mono text-zinc-600 dark:text-zinc-300 whitespace-nowrap">${{ number_format($t['price'], $t['price'] < 1 ? 4 : 2) }}</span>
                        @if ($t['change_pct'] !== null)
                            <span class="text-xs font-semibold whitespace-nowrap {{ $t['change_pct'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                {{ $t['change_pct'] >= 0 ? '+' : '' }}{{ number_format($t['change_pct'], 2) }}% {{ $t['change_pct'] >= 0 ? '↑' : '↓' }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
