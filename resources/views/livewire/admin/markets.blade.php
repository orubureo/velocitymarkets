<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Markets</flux:heading>
            <flux:text class="text-zinc-500">Manage trading markets and pairs.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">Add Market</flux:button>
    </div>

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
                @forelse ($markets as $market)
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
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="{{ $market->is_active ? 'outline' : 'primary' }}" icon="{{ $market->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $market->id }})">
                                    {{ $market->is_active ? 'Disable' : 'Enable' }}
                                </flux:button>
                                <flux:button size="sm" variant="outline" icon="pencil" wire:click="openEditModal({{ $market->id }})" aria-label="Edit {{ $market->symbol }}" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $market->id }})" aria-label="Delete {{ $market->symbol }}" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center text-zinc-500 py-10">No markets yet — add one to get started.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="market-form-modal" class="max-w-md md:min-w-md" wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Edit Market' : 'Add New Market' }}</flux:heading>

            <div class="grid grid-cols-1 gap-4">
                <flux:input wire:model="symbol" label="Symbol" placeholder="BTCUSDT" />
                <flux:input wire:model="displayName" label="Display Name" placeholder="BTC/USDT" />
                <flux:input wire:model="coingeckoId" label="CoinGecko ID" placeholder="bitcoin" />
                <flux:input wire:model="tradingviewSymbol" label="TradingView Symbol" placeholder="BINANCE:BTCUSDT" />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $editingId ? 'Save Changes' : 'Add Market' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="market-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $deletingSymbol }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @if ($deletingOpenTradesCount > 0)
                    <strong>{{ $deletingOpenTradesCount }}</strong> open {{ Str::plural('trade', $deletingOpenTradesCount) }} on this pair will be voided and refunded at the next settlement, since there'll be no market left to price them against. This cannot be undone.
                @else
                    No open trades are on this pair right now, so this is safe to delete. This cannot be undone.
                @endif
            </flux:callout>

            <flux:input wire:model.live="deleteConfirmation" label="Type DELETE to confirm" placeholder="DELETE" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteMarket" wire:loading.attr="disabled" wire:target="deleteMarket" :disabled="$deleteConfirmation !== 'DELETE'">
                    Delete Permanently
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
