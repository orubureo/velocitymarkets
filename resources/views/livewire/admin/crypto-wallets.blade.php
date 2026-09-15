<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Crypto Wallets</flux:heading>
            <flux:text class="text-zinc-500">Manage the deposit addresses shown to users.</flux:text>
        </div>
    </div>

    {{-- Add Wallet Form --}}
    <flux:card class="border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="flex items-center gap-3 mb-5">
            <div class="stat-icon-brand">
                <flux:icon name="plus-circle" class="size-5" />
            </div>
            <flux:heading size="md">Add Deposit Address</flux:heading>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <flux:select wire:model.live="currency" label="Currency">
                <flux:select.option value="BTC">BTC</flux:select.option>
                <flux:select.option value="ETH">ETH</flux:select.option>
                <flux:select.option value="USDT">USDT</flux:select.option>
                <flux:select.option value="SOL">SOL</flux:select.option>
            </flux:select>
            @if ($currency === 'USDT')
                <flux:select wire:model="network" label="Network">
                    <flux:select.option value="">Select network&hellip;</flux:select.option>
                    <flux:select.option value="TRC20">TRC20</flux:select.option>
                    <flux:select.option value="ERC20">ERC20</flux:select.option>
                    <flux:select.option value="BEP20">BEP20</flux:select.option>
                </flux:select>
            @else
                <div class="flex items-end">
                    <flux:text size="sm" class="text-zinc-500 pb-2.5">{{ $currency }} uses a single address &mdash; no network needed.</flux:text>
                </div>
            @endif
            <flux:input wire:model="address" label="Wallet Address" placeholder="0x... / bc1... / T..." class="sm:col-span-1" />
        </div>
        <div class="mt-4">
            <flux:button variant="primary" icon="plus" wire:click="addWallet">Add Address</flux:button>
        </div>
    </flux:card>

    {{-- Wallets Table --}}
    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>Currency</flux:table.column>
                <flux:table.column>Network</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($wallets as $wallet)
                    <flux:table.row wire:key="wallet-{{ $wallet->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $wallet->currency }}</flux:table.cell>
                        <flux:table.cell>{{ $wallet->network ?? '—' }}</flux:table.cell>
                        <flux:table.cell class="font-mono text-xs text-zinc-600 dark:text-zinc-400 max-w-xs truncate">{{ $wallet->address }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $wallet->is_active ? 'lime' : 'zinc' }}">
                                {{ $wallet->is_active ? 'Active' : 'Disabled' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button size="sm" variant="{{ $wallet->is_active ? 'outline' : 'primary' }}" icon="{{ $wallet->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $wallet->id }})">
                                {{ $wallet->is_active ? 'Disable' : 'Enable' }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
