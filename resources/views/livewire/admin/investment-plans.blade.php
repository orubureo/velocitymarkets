<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Investment Plans</flux:heading>
            <flux:text class="text-zinc-500">Manage the investment plans users can buy into.</flux:text>
        </div>
    </div>

    {{-- Add Plan Form --}}
    <flux:card class="border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="flex items-center gap-3 mb-5">
            <div class="stat-icon-brand">
                <flux:icon name="plus-circle" class="size-5" />
            </div>
            <flux:heading size="md">Add New Plan</flux:heading>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <flux:input wire:model="name" label="Name" placeholder="Starter Plan" />
            <flux:input wire:model="description" label="Description (optional)" placeholder="Short description" />
            <flux:input wire:model="minAmount" label="Min Amount (USD)" type="number" step="0.01" placeholder="100" />
            <flux:input wire:model="maxAmount" label="Max Amount (USD)" type="number" step="0.01" placeholder="999" />
            <flux:input wire:model="roiPercent" label="Total ROI (%)" type="number" step="0.01" placeholder="15" />
            <flux:input wire:model="durationDays" label="Duration (days)" type="number" placeholder="15" />
        </div>
        <div class="mt-4">
            <flux:button variant="primary" icon="plus" wire:click="addPlan">Add Plan</flux:button>
        </div>
    </flux:card>

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
                @foreach ($plans as $plan)
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
                            <flux:button size="sm" variant="{{ $plan->is_active ? 'outline' : 'primary' }}" icon="{{ $plan->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $plan->id }})">
                                {{ $plan->is_active ? 'Disable' : 'Enable' }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
