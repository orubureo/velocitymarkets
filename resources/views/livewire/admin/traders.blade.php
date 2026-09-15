<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Traders</flux:heading>
            <flux:text class="text-zinc-500">Manage the expert traders users can copy.</flux:text>
        </div>
    </div>

    {{-- Add Trader Form --}}
    <flux:card class="border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="flex items-center gap-3 mb-5">
            <div class="stat-icon-brand">
                <flux:icon name="plus-circle" class="size-5" />
            </div>
            <flux:heading size="md">Add New Trader</flux:heading>
        </div>
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
        <div class="mt-4">
            <flux:button variant="primary" icon="plus" wire:click="addTrader">Add Trader</flux:button>
        </div>
    </flux:card>

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
                @foreach ($traders as $trader)
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
                            <flux:button size="sm" variant="{{ $trader->is_active ? 'outline' : 'primary' }}" icon="{{ $trader->is_active ? 'pause' : 'play' }}" wire:click="toggleActive({{ $trader->id }})">
                                {{ $trader->is_active ? 'Disable' : 'Enable' }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
