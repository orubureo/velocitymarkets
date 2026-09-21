<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Account Tiers</flux:heading>
            <flux:text class="text-zinc-500">Manage the account upgrade plans users can buy into.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">Add Tier</flux:button>
    </div>

    {{-- Tiers Table --}}
    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Price</flux:table.column>
                <flux:table.column>Daily Profit</flux:table.column>
                <flux:table.column>Total Return</flux:table.column>
                <flux:table.column>Referral Bonus</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($tiers as $tier)
                    <flux:table.row wire:key="tier-{{ $tier->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $tier->name }}</flux:table.cell>
                        <flux:table.cell class="font-mono">${{ number_format($tier->price, 2) }}</flux:table.cell>
                        <flux:table.cell class="font-mono text-green-500">{{ $tier->daily_profit_percent }}%</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $tier->total_return_percent }}%</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $tier->referral_bonus_percent }}%</flux:table.cell>
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
                        <flux:table.cell colspan="7" class="text-center text-zinc-500 py-10">No account tiers yet — add one to get started.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="account-tier-form-modal" class="max-w-md md:min-w-md" wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Edit Tier' : 'Add New Tier' }}</flux:heading>

            <div class="grid grid-cols-1 gap-4">
                <flux:input wire:model="name" label="Name" placeholder="Classic" />
                <flux:input wire:model="description" label="Description (optional)" placeholder="Upgrade your account level" />
                <flux:input wire:model="price" label="Price (USD)" type="number" step="0.01" placeholder="500" />
                <flux:input wire:model="dailyProfitPercent" label="Daily Profit (%)" type="number" step="0.01" placeholder="1.5" />
                <flux:input wire:model="totalReturnPercent" label="Total Return (%)" type="number" step="0.01" placeholder="45" />
                <flux:input wire:model="referralBonusPercent" label="Referral Bonus (%)" type="number" step="0.01" placeholder="5" />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $editingId ? 'Save Changes' : 'Add Tier' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="account-tier-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $deletingName }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @if ($deletingUsersCount > 0)
                    <strong>{{ $deletingUsersCount }}</strong> {{ Str::plural('user', $deletingUsersCount) }} currently on this tier will be un-assigned from it (their tier will just be cleared, no other data is lost). This cannot be undone.
                @else
                    No users are currently on this tier, so this is safe to delete. This cannot be undone.
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
