<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Markets</flux:heading>
            <flux:text class="text-zinc-500">Manage trading markets and pairs.</flux:text>
        </div>
    </div>

    {{-- Add Market Form --}}
    <flux:card class="border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="flex items-center gap-3 mb-5">
            <div class="stat-icon-brand">
                <flux:icon name="plus-circle" class="size-5" />
            </div>
            <flux:heading size="md">Add New Market</flux:heading>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <flux:input wire:model="symbol" label="Symbol" placeholder="BTCUSDT" />
            <flux:input wire:model="displayName" label="Display Name" placeholder="BTC/USDT" />
            <flux:input wire:model="coingeckoId" label="CoinGecko ID" placeholder="bitcoin" />
            <flux:input wire:model="tradingviewSymbol" label="TradingView Symbol" placeholder="BINANCE:BTCUSDT" />
        </div>
        <div class="mt-4">
            <flux:button variant="primary" icon="plus" wire:click="addMarket">Add Market</flux:button>
        </div>
    </flux:card>

    {{-- Markets Table --}}
    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>Symbol</flux:table.column>
                <flux:table.column>Display Name</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($markets as $market)
                    <flux:table.row wire:key="market-{{ $market->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <span class="font-mono font-semibold text-zinc-900 dark:text-white">{{ $market->symbol }}</span>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-600 dark:text-zinc-300">{{ $market->display_name }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $market->is_active ? 'lime' : 'zinc' }}">
                                {{ $market->is_active ? 'Active' : 'Disabled' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button size="sm" variant="{{ $market->is_active ? 'outline' : 'primary' }}" icon="{{ $market->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $market->id }})">
                                {{ $market->is_active ? 'Disable' : 'Enable' }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
