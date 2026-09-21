<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Crypto Wallets</flux:heading>
            <flux:text class="text-zinc-500">Manage the deposit addresses shown to users.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">Add Address</flux:button>
    </div>

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
                @forelse ($wallets as $wallet)
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
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="{{ $wallet->is_active ? 'outline' : 'primary' }}" icon="{{ $wallet->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $wallet->id }})">
                                    {{ $wallet->is_active ? 'Disable' : 'Enable' }}
                                </flux:button>
                                <flux:button size="sm" variant="outline" icon="pencil" wire:click="openEditModal({{ $wallet->id }})" aria-label="Edit {{ $wallet->currency }} address" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $wallet->id }})" aria-label="Delete {{ $wallet->currency }} address" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-zinc-500 py-10">No deposit addresses yet — add one to get started.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="crypto-wallet-form-modal" class="max-w-md md:min-w-md" wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Edit Deposit Address' : 'Add Deposit Address' }}</flux:heading>

            <div class="grid grid-cols-1 gap-4">
                <flux:select wire:model.live="currency" label="Currency">
                    <flux:select.option value="BTC">BTC</flux:select.option>
                    <flux:select.option value="ETH">ETH</flux:select.option>
                    <flux:select.option value="USDT">USDT</flux:select.option>
                    <flux:select.option value="SOL">SOL</flux:select.option>
                </flux:select>
                <flux:select wire:model="network" label="Network">
                    <flux:select.option value="">Select network&hellip;</flux:select.option>
                    @foreach ($networkOptions as $option)
                        <flux:select.option value="{{ $option }}">{{ $option }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="address" label="Wallet Address" placeholder="0x... / bc1... / T..." />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $editingId ? 'Save Changes' : 'Add Address' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="crypto-wallet-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $deletingLabel }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @if ($deletingDepositsCount > 0)
                    <strong>{{ $deletingDepositsCount }}</strong> past deposit {{ Str::plural('record', $deletingDepositsCount) }} reference this address and will no longer resolve to a wallet once it's gone. This cannot be undone.
                @else
                    No deposit records reference this address, so this is safe to delete. This cannot be undone.
                @endif
            </flux:callout>

            <flux:input wire:model.live="deleteConfirmation" label="Type DELETE to confirm" placeholder="DELETE" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteWallet" wire:loading.attr="disabled" wire:target="deleteWallet" :disabled="$deleteConfirmation !== 'DELETE'">
                    Delete Permanently
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
