<div class="flex flex-col gap-6 stagger-children">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl">Crypto Orders</flux:heading>
            <flux:text class="text-zinc-500 text-sm">View your active and completed trades across every pair.</flux:text>
        </div>
        <flux:button variant="outline" icon="arrow-left" :href="route('trade')" wire:navigate>Back to Trading</flux:button>
    </div>

    @php
        $baseCurrency = fn (string $symbol) => preg_replace('/USDT$/', '', $symbol) ?: $symbol;
        $iconFor = function (string $symbol) use ($markets, $marketIcons) {
            $market = $markets->firstWhere('symbol', $symbol);
            return $market ? ($marketIcons[$market->coingecko_id] ?? null) : null;
        };
    @endphp

    <flux:card class="trading-card !p-0 overflow-hidden">
        <div class="flex items-center gap-6 border-b border-zinc-200 dark:border-zinc-700 px-5 pt-4">
            <button type="button" wire:click="$set('tab', 'active')"
                class="text-sm font-semibold pb-3 -mb-px cursor-pointer transition-colors {{ $tab === 'active' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                Active Orders ({{ $activeTrades->count() }})
            </button>
            <button type="button" wire:click="$set('tab', 'completed')"
                class="text-sm font-semibold pb-3 -mb-px cursor-pointer transition-colors {{ $tab === 'completed' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                Completed ({{ $completedTrades->count() }})
            </button>
        </div>

        <div class="p-5 flex flex-col gap-2">
            @if ($tab === 'active')
                @forelse ($activeTrades as $trade)
                    <flux:card class="trading-card group flex items-center justify-between !py-3" wire:key="order-active-{{ $trade->id }}">
                        <div class="flex items-center gap-3">
                            <x-crypto-icon :currency="$baseCurrency($trade->asset)" :url="$iconFor($trade->asset)" class="size-9 shrink-0" />
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
                                {{ $trade->expires_at->isFuture() ? $trade->expires_at->diffForHumans(null, true).' left' : 'Settling…' }}
                            </div>
                            <div class="font-mono font-bold text-sm text-zinc-900 dark:text-white">${{ number_format($trade->stake, 2) }}</div>
                        </div>
                    </flux:card>
                @empty
                    <div class="flex flex-col items-center gap-3 text-center py-14">
                        <flux:icon name="inbox" class="size-8 text-zinc-300 dark:text-zinc-700" />
                        <flux:heading size="md">No active orders</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">All your pending trades will appear here.</flux:text>
                    </div>
                @endforelse
            @else
                @forelse ($completedTrades as $trade)
                    <flux:card class="trading-card group flex items-center justify-between !py-3" wire:key="order-completed-{{ $trade->id }}">
                        <div class="flex items-center gap-3">
                            <x-crypto-icon :currency="$baseCurrency($trade->asset)" :url="$iconFor($trade->asset)" class="size-9 shrink-0" />
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
                    <div class="flex flex-col items-center gap-3 text-center py-14">
                        <flux:icon name="inbox" class="size-8 text-zinc-300 dark:text-zinc-700" />
                        <flux:heading size="md">No completed orders yet</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">Your settled trades will show up here.</flux:text>
                    </div>
                @endforelse
            @endif
        </div>
    </flux:card>
</div>
