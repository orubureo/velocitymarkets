<div wire:ignore
    class="sticky top-16 lg:top-0 z-[5] shrink-0 w-full border-b border-zinc-200 dark:border-zinc-800 overflow-hidden"
>
    {{-- Real prices from the same PriceService the rest of the app uses, not
         fabricated. Runs standalone — no TradingView embed, so nothing here
         depends on a third-party domain that ad-blockers commonly flag. --}}
    @if (! empty($markets))
        <div class="relative bg-white dark:bg-zinc-900">
            <div class="ticker-track py-3">
                @foreach ([...$markets, ...$markets] as $t)
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
