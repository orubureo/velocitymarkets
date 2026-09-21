<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Signal Tiers</flux:heading>
            <flux:text class="text-zinc-500">Manage the buy signal allocation tiers users can buy into.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">Add Tier</flux:button>
    </div>

    {{-- Tiers Table --}}
    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Allocation</flux:table.column>
                <flux:table.column>Win Rate</flux:table.column>
                <flux:table.column>ROI</flux:table.column>
                <flux:table.column>Duration</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($tiers as $tier)
                    <flux:table.row wire:key="signal-tier-{{ $tier->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $tier->name }}</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $tier->percent }}%</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $tier->win_rate_percent }}%</flux:table.cell>
                        <flux:table.cell class="font-mono text-green-500">{{ $tier->roi_percent }}%</flux:table.cell>
                        <flux:table.cell>{{ $tier->duration_days }} days</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $tier->is_active ? 'lime' : 'zinc' }}">
                                {{ $tier->is_active ? 'Active' : 'Disabled' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="{{ $tier->is_active ? 'outline' : 'primary' }}" icon="{{ $tier->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $tier->id }})">
                                    {{ $tier->is_active ? 'Disable' : 'Enable' }}
                                </flux:button>
                                <flux:button size="sm" variant="outline" icon="pencil" wire:click="openEditModal({{ $tier->id }})" aria-label="Edit {{ $tier->name }}" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $tier->id }})" aria-label="Delete {{ $tier->name }}" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" class="text-center text-zinc-500 py-10">No signal tiers yet — add one to get started.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="signal-tier-form-modal" class="max-w-md md:min-w-md" wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Edit Tier' : 'Add New Tier' }}</flux:heading>

            <div class="grid grid-cols-1 gap-4">
                <flux:input wire:model="name" label="Name" placeholder="Starter Signal" />
                <flux:input wire:model="description" label="Description (optional)" placeholder="Signal allocation plan" />
                <flux:input wire:model="percent" label="Balance Allocation (%)" type="number" placeholder="25" />
                <flux:input wire:model="winRatePercent" label="Win Rate (%)" type="number" step="0.01" placeholder="65" />
                <flux:input wire:model="roiPercent" label="Total ROI (%)" type="number" step="0.01" placeholder="20" />
                <flux:input wire:model="durationDays" label="Duration (days)" type="number" placeholder="7" />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $editingId ? 'Save Changes' : 'Add Tier' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="signal-tier-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $deletingName }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @if ($deletingSignalsCount > 0)
                    This will permanently delete <strong>{{ $deletingSignalsCount }}</strong> user signal {{ Str::plural('purchase', $deletingSignalsCount) }} on this tier — not just disable it. This cannot be undone.
                @else
                    No users currently hold a signal on this tier, so this is safe to delete. This cannot be undone.
                @endif
            </flux:callout>

            <flux:input wire:model.live="deleteConfirmation" label="Type DELETE to confirm" placeholder="DELETE" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteTier" wire:loading.attr="disabled" wire:target="deleteTier" :disabled="$deleteConfirmation !== 'DELETE'">
                    Delete Permanently
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
