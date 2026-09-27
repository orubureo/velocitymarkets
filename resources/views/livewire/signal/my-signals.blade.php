<div class="flex flex-col gap-8 stagger-children">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">My Signals</flux:heading>
            <flux:text class="text-zinc-500">Track the signals you've bought and their payouts.</flux:text>
        </div>
        <flux:button variant="primary" icon="bolt" :href="route('buy-signal')" wire:navigate>
            Browse Signals
        </flux:button>
    </div>

    @if ($mySignals->isNotEmpty())
        <flux:card class="trading-card !p-0 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Signal</flux:table.column>
                    <flux:table.column>Allocated</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Progress</flux:table.column>
                    <flux:table.column>ROI Paid</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($mySignals as $signal)
                        <flux:table.row wire:key="my-signal-{{ $signal->id }}">
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-white">{{ $signal->tier->name }} ({{ $signal->percent }}%)</flux:table.cell>
                            <flux:table.cell class="font-mono">${{ number_format($signal->amount, 2) }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" color="{{ match ($signal->status) {
                                    'active' => 'lime',
                                    'completed' => 'violet',
                                    default => 'zinc',
                                } }}">
                                    {{ ucfirst($signal->status) }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2 w-32">
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-1.5">
                                        <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $signal->progressPercent() }}%"></div>
                                    </div>
                                    <span class="text-xs text-zinc-500 shrink-0">{{ $signal->daysRemaining() }}d left</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="font-mono text-green-500">${{ number_format($signal->totalRoiPaid(), 2) }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card>
    @else
        <flux:card class="trading-card flex flex-col items-center justify-center gap-3 py-20 text-center">
            <div class="size-16 rounded-full bg-teal-500/10 flex items-center justify-center">
                <flux:icon name="bolt" class="size-8 text-teal-500" />
            </div>
            <flux:heading size="lg" class="text-zinc-900 dark:text-white">No Signals Yet</flux:heading>
            <flux:text class="text-zinc-500 max-w-sm">
                Buy into a signal tier to start following automated trade allocations.
            </flux:text>
            <flux:button variant="primary" icon="bolt" :href="route('buy-signal')" wire:navigate class="mt-2">
                Browse Signals
            </flux:button>
        </flux:card>
    @endif
</div>
