<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Investment Plans</flux:heading>
            <flux:text class="text-zinc-500">Manage the investment plans users can buy into.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">Add Plan</flux:button>
    </div>

    {{-- Plans Table --}}
    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Min &mdash; Max</flux:table.column>
                <flux:table.column>ROI</flux:table.column>
                <flux:table.column>Duration</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($plans as $plan)
                    <flux:table.row wire:key="plan-{{ $plan->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $plan->name }}</flux:table.cell>
                        <flux:table.cell class="font-mono">${{ number_format($plan->min_amount, 0) }} &ndash; ${{ number_format($plan->max_amount, 0) }}</flux:table.cell>
                        <flux:table.cell class="font-mono text-green-500">{{ $plan->roi_percent }}%</flux:table.cell>
                        <flux:table.cell>{{ $plan->duration_days }} days</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="{{ $plan->is_active ? 'lime' : 'zinc' }}">
                                {{ $plan->is_active ? 'Active' : 'Disabled' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="{{ $plan->is_active ? 'outline' : 'primary' }}" icon="{{ $plan->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $plan->id }})">
                                    {{ $plan->is_active ? 'Disable' : 'Enable' }}
                                </flux:button>
                                <flux:button size="sm" variant="outline" icon="pencil" wire:click="openEditModal({{ $plan->id }})" aria-label="Edit {{ $plan->name }}" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $plan->id }})" aria-label="Delete {{ $plan->name }}" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center text-zinc-500 py-10">No investment plans yet — add one to get started.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="investment-plan-form-modal" class="max-w-md md:min-w-md" wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Edit Plan' : 'Add New Plan' }}</flux:heading>

            <div class="grid grid-cols-1 gap-4">
                <flux:input wire:model="name" label="Name" placeholder="Starter Plan" />
                <flux:input wire:model="description" label="Description (optional)" placeholder="Short description" />
                <flux:input wire:model="minAmount" label="Min Amount (USD)" type="number" step="0.01" placeholder="100" />
                <flux:input wire:model="maxAmount" label="Max Amount (USD)" type="number" step="0.01" placeholder="999" />
                <flux:input wire:model="roiPercent" label="Total ROI (%)" type="number" step="0.01" placeholder="15" />
                <flux:input wire:model="durationDays" label="Duration (days)" type="number" placeholder="15" />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $editingId ? 'Save Changes' : 'Add Plan' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="investment-plan-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $deletingName }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @if ($deletingInvestmentsCount > 0)
                    This will permanently delete <strong>{{ $deletingInvestmentsCount }}</strong> user {{ Str::plural('investment', $deletingInvestmentsCount) }} in this plan — not just disable it. This cannot be undone.
                @else
                    No users are currently invested in this plan, so this is safe to delete. This cannot be undone.
                @endif
            </flux:callout>

            <flux:input wire:model.live="deleteConfirmation" label="Type DELETE to confirm" placeholder="DELETE" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deletePlan" wire:loading.attr="disabled" wire:target="deletePlan" :disabled="$deleteConfirmation !== 'DELETE'">
                    Delete Permanently
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
