<div class="flex flex-col gap-8 stagger-children">
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Buy Signal</flux:heading>
        <flux:text class="text-zinc-500">Follow a trading signal with a slice of your balance.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    {{-- Available Signal Tiers --}}
    <div class="flex flex-col gap-4">
        <flux:heading size="lg" class="text-zinc-900 dark:text-white">Available Signal Tiers</flux:heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse ($tiers as $tier)
                <flux:card wire:key="signal-tier-{{ $tier->id }}" class="trading-card flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <flux:heading size="lg">{{ $tier->name }}</flux:heading>
                            <flux:text size="sm" class="text-zinc-500">{{ $tier->description ?: 'Signal allocation plan' }}</flux:text>
                        </div>
                        <div class="stat-icon-brand !rounded-full !size-11 flex items-center justify-center !p-0 shrink-0">
                            <span class="text-sm font-bold">{{ $tier->percent }}%</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Win Rate</span>
                            <span class="font-mono font-semibold text-zinc-900 dark:text-white">{{ $tier->win_rate_percent }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Total ROI</span>
                            <span class="font-mono font-semibold text-green-500">{{ $tier->roi_percent }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Daily Est.</span>
                            <span class="font-mono font-semibold text-green-500">{{ $tier->dailyRoiPercent() }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Duration</span>
                            <span class="font-mono font-semibold text-zinc-900 dark:text-white">{{ $tier->duration_days }} Days</span>
                        </div>
                    </div>

                    <div class="p-3 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl">
                        <flux:text size="xs" class="text-zinc-500 uppercase tracking-wide font-semibold">You'll Allocate</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white mt-0.5">
                            ${{ number_format($balance * $tier->percent / 100, 2) }}
                        </flux:heading>
                    </div>

                    <flux:button variant="primary" class="w-full" icon="bolt" wire:click="openBuyModal({{ $tier->id }})">
                        Buy Signal
                    </flux:button>
                </flux:card>
            @empty
                <flux:card class="sm:col-span-2 lg:col-span-4 text-center py-12 text-zinc-500">
                    No signal tiers are available right now.
                </flux:card>
            @endforelse
        </div>
    </div>

    {{-- My Signals --}}
    <div class="flex flex-col gap-4">
        <flux:heading size="lg" class="text-zinc-900 dark:text-white">My Signals</flux:heading>

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
                    @forelse ($mySignals as $signal)
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
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-zinc-500 py-8">
                                You haven't bought any signal yet.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>

    <flux:modal name="buy-signal-modal" class="max-w-md md:min-w-md" wire:model="showBuyModal">
        @if ($selectedTier)
            @php $allocation = round($balance * $selectedTier->percent / 100, 2); @endphp
            <div class="flex flex-col gap-5">
                <div>
                    <flux:heading size="lg">Buy {{ $selectedTier->name }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Confirm your signal purchase below.</flux:text>
                </div>

                <div class="grid grid-cols-3 gap-2 p-3 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl">
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Win Rate</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">{{ $selectedTier->win_rate_percent }}%</flux:heading>
                    </div>
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Total ROI</flux:text>
                        <flux:heading size="sm" class="font-mono text-green-500">{{ $selectedTier->roi_percent }}%</flux:heading>
                    </div>
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Duration</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">{{ $selectedTier->duration_days }}d</flux:heading>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl border-2 border-teal-500 bg-teal-500/5">
                    <div>
                        <flux:text class="font-semibold text-zinc-900 dark:text-white block">{{ $selectedTier->percent }}% of Your Balance</flux:text>
                        <flux:text size="sm" class="text-zinc-500">Deducted from your account balance</flux:text>
                    </div>
                    <flux:heading size="lg" class="font-mono text-zinc-900 dark:text-white">${{ number_format($allocation, 2) }}</flux:heading>
                </div>

                <flux:text size="sm" class="text-zinc-500">Your balance: <span class="font-mono text-zinc-900 dark:text-white">${{ number_format($balance, 2) }}</span></flux:text>

                @if ($allocation <= 0)
                    <flux:callout variant="warning" icon="exclamation-triangle">
                        Your balance is too low to buy this signal. Deposit more funds to continue.
                    </flux:callout>
                @endif

                <div class="flex gap-3">
                    <flux:button variant="outline" wire:click="closeBuyModal" class="flex-1">Cancel</flux:button>
                    <flux:button variant="primary" wire:click="buy" wire:loading.attr="disabled" wire:target="buy" class="flex-1" :disabled="$allocation <= 0">
                        Confirm Purchase
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
