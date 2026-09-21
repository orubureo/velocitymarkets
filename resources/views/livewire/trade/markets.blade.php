<div class="flex flex-col gap-6 stagger-children">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl">Markets &amp; Trading</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Browse live prices and open a trade on any pair.</flux:text>
        </div>
        <div class="flex items-center gap-2">
            <flux:button variant="outline" icon="briefcase" :href="route('trade.portfolio')" wire:navigate>Portfolio</flux:button>
            <flux:button variant="outline" icon="queue-list" :href="route('trade.orders')" wire:navigate>Orders</flux:button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Available Balance</span>
                <div class="stat-icon-brand !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="wallet" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-white font-mono">${{ number_format($balance, 2) }}</h2>
        </flux:card>

        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Active Stake</span>
                <div class="stat-icon-sky !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="currency-dollar" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-white font-mono">${{ number_format($activeStake, 2) }}</h2>
        </flux:card>

        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total P/L</span>
                <div class="{{ $totalPnl >= 0 ? 'stat-icon-up' : 'stat-icon-down' }} !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="{{ $totalPnl >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold font-mono {{ $totalPnl >= 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ $totalPnl >= 0 ? '+' : '-' }}${{ number_format(abs($totalPnl), 2) }}
            </h2>
        </flux:card>

        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Open Trades</span>
                <div class="stat-icon-amber !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="clock" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-white font-mono">{{ $openTradesCount }}</h2>
        </flux:card>
    </div>

    {{-- Markets table --}}
    <flux:card class="trading-card !p-0 overflow-hidden">
        <div class="flex items-center justify-between gap-3 flex-wrap p-4 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <flux:icon name="link" class="size-5 text-teal-500" />
                <flux:heading size="lg">All Cryptocurrencies</flux:heading>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="$set('watchlistOnly', {{ $watchlistOnly ? 'false' : 'true' }})"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-colors {{ $watchlistOnly ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                    <flux:icon name="star" variant="{{ $watchlistOnly ? 'solid' : 'outline' }}" class="size-3.5" />
                    Watchlist
                </button>
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" size="sm" placeholder="Search…" class="w-40 sm:w-56" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="w-10 hidden md:table-cell">#</flux:table.column>
                    <flux:table.column>Cryptocurrency</flux:table.column>
                    <flux:table.column class="hidden md:table-cell">Price</flux:table.column>
                    <flux:table.column class="hidden md:table-cell">24h Change</flux:table.column>
                    <flux:table.column class="hidden md:table-cell">Market Cap</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($markets as $i => $market)
                        @php
                            $baseCurrency = preg_replace('/USDT$/', '', $market['symbol']) ?: $market['symbol'];
                            $isWatchlisted = in_array($market['symbol'], $watchlisted, true);
                            $change = $market['change_pct'] ?? 0;
                        @endphp
                        <flux:table.row wire:key="market-{{ $market['symbol'] }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <flux:table.cell class="text-zinc-400 hidden md:table-cell">{{ $i + 1 }}</flux:table.cell>
                            <flux:table.cell>
                                <a href="{{ route('trade.show', $market['symbol']) }}" wire:navigate class="flex items-center gap-2.5 min-w-0">
                                    <x-crypto-icon :currency="$baseCurrency" :url="$market['image']" class="size-8 shrink-0" />
                                    <div class="min-w-0">
                                        <div class="font-bold text-zinc-900 dark:text-white truncate">{{ $market['display_name'] }}</div>
                                        <div class="text-xs text-zinc-500">{{ $market['symbol'] }}</div>
                                        {{-- Price/change move in here on mobile since their own columns are hidden --}}
                                        <div class="md:hidden flex items-center gap-1.5 mt-0.5 font-mono text-xs">
                                            <span class="text-zinc-700 dark:text-zinc-300">${{ number_format($market['price'], $market['price'] < 1 ? 4 : 2) }}</span>
                                            <span class="inline-flex items-center gap-0.5 font-semibold {{ $change >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                                <flux:icon name="{{ $change >= 0 ? 'arrow-up' : 'arrow-down' }}" class="size-2.5" />
                                                {{ number_format(abs($change), 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </flux:table.cell>
                            <flux:table.cell class="font-mono hidden md:table-cell">${{ number_format($market['price'], $market['price'] < 1 ? 4 : 2) }}</flux:table.cell>
                            <flux:table.cell class="hidden md:table-cell">
                                <span class="inline-flex items-center gap-1 font-mono font-semibold {{ $change >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                    <flux:icon name="{{ $change >= 0 ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                                    {{ number_format(abs($change), 2) }}%
                                </span>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 font-mono hidden md:table-cell">
                                {{ $market['market_cap'] ? '$'.\Illuminate\Support\Number::abbreviate($market['market_cap'], maxPrecision: 2) : '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('trade.show', $market['symbol']) }}" wire:navigate
                                        class="px-3 py-1.5 rounded-lg bg-teal-500 hover:bg-teal-600 text-white text-xs font-bold transition-colors whitespace-nowrap">
                                        Trade
                                    </a>
                                    <button type="button" wire:click="toggleWatchlist('{{ $market['symbol'] }}')"
                                        wire:key="star-{{ $market['symbol'] }}"
                                        title="{{ $isWatchlisted ? 'Remove from watchlist' : 'Add to watchlist' }}"
                                        class="p-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                        <flux:icon name="star" variant="{{ $isWatchlisted ? 'solid' : 'outline' }}"
                                            class="size-4 {{ $isWatchlisted ? 'text-amber-400' : 'text-zinc-400' }}" />
                                    </button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center text-zinc-500 py-10">
                                {{ $watchlistOnly ? "You haven't added any markets to your watchlist yet." : 'No markets match your search.' }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
</div>
