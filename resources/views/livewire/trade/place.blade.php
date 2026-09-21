<div class="flex flex-col gap-6 stagger-children" x-data="{ stake: @entangle('stake'), balance: @js($balance ?? 0) }">
    <div>
        <flux:link :href="route('trade')" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-teal-500 mb-1">
            <flux:icon name="chevron-left" class="size-3.5" /> All Markets
        </flux:link>
        <flux:heading size="xl">Predict {{ $currentMarket->display_name ?? $asset }}</flux:heading>
        <flux:text class="text-zinc-500 text-sm">Choose Rise or Fall and lock in your stake before it expires.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">{{ session('status') }}</flux:callout>
    @endif

    @php
        $baseCurrency = fn (string $symbol) => preg_replace('/USDT$/', '', $symbol) ?: $symbol;
        $iconFor = function (string $symbol) use ($markets, $marketIcons) {
            $market = $markets->firstWhere('symbol', $symbol);
            return $market ? ($marketIcons[$market->coingecko_id] ?? null) : null;
        };
        $currentMarket = $markets->firstWhere('symbol', $asset);
    @endphp

    <div class="flex flex-col md:flex-row gap-6 items-start w-full">
        {{-- Left Column: Trade Form (1/3 width) --}}
        <div class="w-full md:w-1/3 shrink-0">
            <flux:card class="trading-card flex flex-col gap-5">
                {{-- Balance --}}
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Available Balance</div>
                        <div class="text-xl font-mono font-bold text-zinc-900 dark:text-white">${{ number_format($balance ?? 0, 2) }}</div>
                    </div>
                    <flux:link :href="route('wallet', ['tab' => 'deposit'])" wire:navigate
                        class="!no-underline hover:!no-underline bg-teal-500/10 hover:bg-teal-500/20 px-3 py-1.5 rounded-full text-xs font-bold">
                        + Deposit
                    </flux:link>
                </div>

                {{-- Amount Input --}}
                <div class="mt-2">
                    <div class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2">Amount (USD)</div>
                    <flux:input wire:model="stake" type="number" step="0.01" icon="currency-dollar" class="font-mono" placeholder="0.00" />
                </div>

                {{-- Quick percentages --}}
                <div class="grid grid-cols-2 gap-2">
                    @foreach([25, 50, 75, 100] as $pct)
                        <button type="button" @click="stake = (balance * {{ $pct / 100 }}).toFixed(2); $wire.set('stake', stake)"
                            class="py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">{{ $pct }}%</button>
                    @endforeach
                </div>

                {{-- Quick amounts --}}
                <div class="grid grid-cols-3 gap-2">
                    @foreach([10, 25, 50, 100, 250, 500] as $amt)
                        <button type="button" @click="stake = {{ $amt }}; $wire.set('stake', {{ $amt }})"
                            class="py-1 text-[10px] sm:text-xs font-medium text-white bg-teal-500 rounded-md hover:bg-teal-600 transition-colors">${{ $amt }}</button>
                    @endforeach
                </div>

                {{-- Duration --}}
                <div class="mt-2">
                    <div class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2">Duration</div>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([1 => '1m', 5 => '5m', 15 => '15m', 30 => '30m', 60 => '1h', 240 => '4h'] as $val => $label)
                            <button type="button" wire:click="$set('expiryMinutes', {{ $val }})"
                                class="py-2 flex flex-col items-center justify-center rounded-md border transition-colors {{ $expiryMinutes == $val ? 'border-teal-500 bg-teal-500/10 text-teal-600 dark:text-teal-400' : 'border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}">
                                <span class="font-bold text-sm">{{ $label }}</span>
                                <span class="text-[10px] text-green-500">+85%</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Profit / Potential Return --}}
                <div class="flex items-center justify-between border-t border-zinc-100 dark:border-zinc-800 pt-5 mt-2">
                    <div>
                        <div class="text-xs text-zinc-500 mb-1">Profit rate</div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-white">Potential return</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-green-500 font-bold mb-1">+85%</div>
                        <div class="text-sm font-bold font-mono text-zinc-900 dark:text-white" x-text="'$' + (stake ? (parseFloat(stake) * 1.85).toFixed(2) : '0.00')"></div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <button wire:click="placeTrade('rise')"
                        class="py-3 rounded-xl font-bold text-white bg-green-500 hover:bg-green-600 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 transition-all shadow-sm hover:shadow-lg hover:shadow-green-500/20">
                        <flux:icon name="arrow-trending-up" class="size-4" />
                        Rise
                    </button>
                    <button wire:click="placeTrade('fall')"
                        class="py-3 rounded-xl font-bold text-white bg-red-500 hover:bg-red-600 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 transition-all shadow-sm hover:shadow-lg hover:shadow-red-500/20">
                        <flux:icon name="arrow-trending-down" class="size-4" />
                        Fall
                    </button>
                </div>
            </flux:card>
        </div>

        {{-- Right Column: Chart and Trades (2/3 width) --}}
        <div class="w-full md:w-2/3 flex flex-col gap-6">
            {{-- Asset Header --}}
            <flux:card class="trading-card flex items-center justify-between !py-4">
                <div class="flex items-center gap-3 min-w-0">
                    <x-crypto-icon :currency="$baseCurrency($asset)" :url="$iconFor($asset)" class="size-10 shrink-0" />
                    <div class="min-w-0">
                        <div class="font-bold text-lg text-zinc-900 dark:text-white truncate">{{ $currentMarket->display_name ?? $asset }}</div>
                        <flux:text class="text-xs text-zinc-500 -mt-1 block">{{ $asset }}</flux:text>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xl font-mono font-bold text-zinc-900 dark:text-white">${{ number_format($currentPrice, 4) }}</div>
                    <div class="text-xs text-zinc-400">Live price</div>
                </div>
            </flux:card>

            {{-- Chart --}}
            <flux:card class="trading-card !p-0 overflow-hidden">
                <div wire:ignore x-data="{
                    symbol: @js($tradingViewSymbol ?? 'BINANCE:BTCUSDT'),
                    tvFailed: false,
                    initWidget(sym, dark) {
                        // `tvGen`/`tvAttempts` are deliberately plain properties on $el, NOT
                        // reactive x-data state: this function runs synchronously inside
                        // `x-effect`, and Alpine tracks every reactive property READ during
                        // that synchronous run as a dependency of the effect. A read-then-
                        // write like `++this.tvGen` on a REACTIVE property would both read
                        // it (registering it as a dependency) and write it (triggering the
                        // effect to re-run) in the same tick — an infinite self-triggering
                        // loop that re-creates the TradingView widget hundreds of times a
                        // second and never lets it settle (this is almost certainly why the
                        // chart failed to render at all on a slower mobile device). Keeping
                        // the counters off the reactive proxy avoids that entirely; `tvFailed`
                        // stays reactive since it's the only thing driving the UI (x-show).
                        this.$el._tvGen = (this.$el._tvGen || 0) + 1;
                        const gen = this.$el._tvGen;
                        this.$el._tvAttempts = 0;
                        this.tvFailed = false;
                        this.tryInitWidget(sym, dark, gen);
                    },
                    tryInitWidget(sym, dark, gen) {
                        if (gen !== this.$el._tvGen) return;
                        if (typeof TradingView === 'undefined') {
                            // s3.tradingview.com is commonly blocked by ad-blockers/network
                            // filters (especially on mobile) — stop polling after ~6s and
                            // show a fallback instead of an indefinitely blank chart.
                            this.$el._tvAttempts++;
                            if (this.$el._tvAttempts > 60) {
                                this.tvFailed = true;
                                return;
                            }
                            setTimeout(() => this.tryInitWidget(sym, dark, gen), 100);
                            return;
                        }
                        const el = document.getElementById('tv_chart_container');
                        if (el) el.innerHTML = '';
                        new TradingView.widget({
                            autosize: true,
                            symbol: sym,
                            interval: '1',
                            timezone: 'Etc/UTC',
                            theme: dark ? 'dark' : 'light',
                            style: '1',
                            locale: 'en',
                            enable_publishing: false,
                            backgroundColor: dark ? '#0A0B10' : '#ffffff',
                            gridColor: dark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)',
                            hide_side_toolbar: false,
                            allow_symbol_change: true,
                            container_id: 'tv_chart_container'
                        });
                    }
                }" x-init="
                    window.addEventListener('tv-symbol-changed', (e) => { symbol = e.detail.symbol; });
                " x-effect="initWidget(symbol, ($flux.appearance === 'dark' || ($flux.appearance === 'system' && $flux.dark)))" class="h-[450px] w-full relative">
                    <div id="tv_chart_container" class="h-full w-full" x-show="!tvFailed"></div>
                    <div x-show="tvFailed" x-cloak class="h-full w-full flex flex-col items-center justify-center gap-2 text-center px-6 bg-zinc-50 dark:bg-zinc-950/60">
                        <flux:icon name="chart-bar-square" class="size-8 text-zinc-300 dark:text-zinc-700" />
                        <flux:text class="text-zinc-500">Live chart unavailable right now.</flux:text>
                        <flux:text size="sm" class="text-zinc-400">Current price: ${{ number_format($currentPrice, 4) }}</flux:text>
                    </div>
                </div>
            </flux:card>

            {{-- Positions --}}
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-6 border-b border-zinc-200 dark:border-zinc-700 pb-2">
                    <button type="button" wire:click="$set('positionsTab', 'active')"
                        class="text-sm font-semibold pb-2 -mb-[9px] cursor-pointer transition-colors {{ $positionsTab === 'active' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                        Active trades
                    </button>
                    <button type="button" wire:click="$set('positionsTab', 'closed')"
                        class="text-sm font-semibold pb-2 -mb-[9px] cursor-pointer transition-colors {{ $positionsTab === 'closed' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                        Closed trades
                    </button>
                </div>

                <div class="mt-2 flex flex-col gap-2">
                    @if ($positionsTab === 'active')
                        @forelse ($openTrades as $trade)
                            <flux:card class="trading-card group flex items-center justify-between !py-3" wire:key="open-trade-{{ $trade->id }}">
                                <div class="flex items-center gap-3">
                                    <x-crypto-icon :currency="$baseCurrency($trade->asset)" :url="$iconFor($trade->asset)" class="size-8 shrink-0" />
                                    <div>
                                        <div class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-1.5">
                                            {{ $trade->asset }}
                                            <flux:badge size="sm" color="{{ $trade->direction === 'rise' ? 'lime' : 'red' }}">{{ strtoupper($trade->direction) }}</flux:badge>
                                        </div>
                                        <div class="text-xs text-zinc-500">Entry ${{ number_format($trade->entry_price, 4) }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-sky-500 font-semibold mb-1 flex items-center justify-end gap-1">
                                        <flux:icon name="clock" class="size-3" />
                                        {{ $trade->expires_at->isFuture() ? $trade->expires_at->diffForHumans(null, true) . ' left' : 'Settling…' }}
                                    </div>
                                    <div class="font-mono font-bold text-sm text-zinc-900 dark:text-white">${{ number_format($trade->stake, 2) }}</div>
                                </div>
                            </flux:card>
                        @empty
                            <div class="text-zinc-500 p-4 text-center text-sm">No active trades.</div>
                        @endforelse
                    @else
                        @forelse ($closedTrades as $trade)
                            <flux:card class="trading-card group flex items-center justify-between !py-3" wire:key="closed-trade-{{ $trade->id }}">
                                <div class="flex items-center gap-3">
                                    <x-crypto-icon :currency="$baseCurrency($trade->asset)" :url="$iconFor($trade->asset)" class="size-8 shrink-0" />
                                    <div>
                                        <div class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-1.5">
                                            {{ $trade->asset }}
                                            <flux:badge size="sm" color="{{ $trade->direction === 'rise' ? 'lime' : 'red' }}">{{ strtoupper($trade->direction) }}</flux:badge>
                                        </div>
                                        <div class="text-xs text-zinc-500">
                                            @if ($trade->status === 'voided')
                                                Market unavailable at settlement
                                            @else
                                                ${{ number_format($trade->entry_price, 4) }} &rarr; ${{ number_format($trade->exit_price, 4) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <flux:badge size="sm" color="{{ $trade->status === 'won' ? 'emerald' : ($trade->status === 'voided' ? 'zinc' : 'red') }}" class="mb-1">
                                        {{ ucfirst($trade->status) }}
                                    </flux:badge>
                                    <div class="font-mono font-bold text-sm {{ $trade->status === 'won' ? 'text-green-500' : ($trade->status === 'voided' ? 'text-zinc-500' : 'text-red-500') }}">
                                        @if ($trade->status === 'won')
                                            +${{ number_format($trade->payout, 2) }}
                                        @elseif ($trade->status === 'voided')
                                            ${{ number_format($trade->stake, 2) }} refunded
                                        @else
                                            -${{ number_format($trade->stake, 2) }}
                                        @endif
                                    </div>
                                </div>
                            </flux:card>
                        @empty
                            <div class="text-zinc-500 p-4 text-center text-sm">No closed trades yet.</div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://s3.tradingview.com/tv.js"></script>
    @endpush
</div>
