<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Traders</flux:heading>
            <flux:text class="text-zinc-500">Manage the expert traders users can copy.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">Add Trader</flux:button>
    </div>

    {{-- Traders Table --}}
    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Tier</flux:table.column>
                <flux:table.column>Risk</flux:table.column>
                <flux:table.column>Win Rate</flux:table.column>
                <flux:table.column>ROI 30d</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($traders as $trader)
                    <flux:table.row wire:key="trader-{{ $trader->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $trader->name }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $trader->tierColor() }}">{{ ucfirst($trader->tier) }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $trader->riskColor() }}">{{ ucfirst($trader->risk_level) }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $trader->win_rate }}%</flux:table.cell>
                        <flux:table.cell class="font-mono text-green-500">+{{ $trader->roi_30d }}%</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $trader->is_active ? 'lime' : 'zinc' }}">
                                {{ $trader->is_active ? 'Active' : 'Disabled' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="{{ $trader->is_active ? 'outline' : 'primary' }}" icon="{{ $trader->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $trader->id }})">
                                    {{ $trader->is_active ? 'Disable' : 'Enable' }}
                                </flux:button>
                                <flux:button size="sm" variant="outline" icon="pencil" wire:click="openEditModal({{ $trader->id }})" aria-label="Edit {{ $trader->name }}" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $trader->id }})" aria-label="Delete {{ $trader->name }}" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" class="text-center text-zinc-500 py-10">No traders yet — add one to get started.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="trader-form-modal" class="max-w-md md:min-w-md" wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Edit Trader' : 'Add New Trader' }}</flux:heading>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Name" placeholder="James Miller" />
                <flux:input wire:model="tagline" label="Tagline (optional)" placeholder="Crypto Top 1%" />
                <flux:select wire:model="tier" label="Tier">
                    <flux:select.option value="verified">Verified</flux:select.option>
                    <flux:select.option value="pro">Pro</flux:select.option>
                    <flux:select.option value="elite">Elite</flux:select.option>
                </flux:select>
                <flux:select wire:model="riskLevel" label="Risk Level">
                    <flux:select.option value="low">Low</flux:select.option>
                    <flux:select.option value="medium">Medium</flux:select.option>
                    <flux:select.option value="high">High</flux:select.option>
                </flux:select>
                <flux:input wire:model="winRate" label="Win Rate (%)" type="number" step="0.01" placeholder="78.4" />
                <flux:input wire:model="roi30d" label="ROI 30d (%)" type="number" step="0.01" placeholder="142.6" />
                <flux:input wire:model="baseCopiers" label="Base Copiers" type="number" step="1" min="0" placeholder="0" />
                <flux:input wire:model="minCopyAmount" label="Min Copy Amount (USD)" type="number" step="0.01" placeholder="100" />
                <flux:input wire:model="maxCopyAmount" label="Max Copy Amount (USD, optional)" type="number" step="0.01" placeholder="10000" />
                <flux:textarea wire:model="bio" label="Bio (optional)" placeholder="Short description of the trader's strategy and background." class="sm:col-span-2" rows="2" />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $editingId ? 'Save Changes' : 'Add Trader' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="trader-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $deletingName }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @if ($deletingSubscriptionsCount > 0)
                    This will permanently delete <strong>{{ $deletingSubscriptionsCount }}</strong> user copy-trading {{ Str::plural('subscription', $deletingSubscriptionsCount) }} to them — not just disable it. This cannot be undone.
                @else
                    No users are currently copying this trader, so this is safe to delete. This cannot be undone.
                @endif
            </flux:callout>

            <flux:input wire:model.live="deleteConfirmation" label="Type DELETE to confirm" placeholder="DELETE" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteTrader" wire:loading.attr="disabled" wire:target="deleteTrader" :disabled="$deleteConfirmation !== 'DELETE'">
                    Delete Permanently
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
