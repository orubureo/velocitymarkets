<div class="flex flex-col gap-8 stagger-children">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Buy Signal</flux:heading>
            <flux:text class="text-zinc-500">Follow a trading signal with a slice of your balance.</flux:text>
        </div>
        <flux:button variant="outline" icon="queue-list" :href="route('buy-signal.my-signals')" wire:navigate>
            My Signals
        </flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    {{-- Available Signal Tiers --}}
    <div class="flex flex-col gap-4">
        <flux:heading size="lg" class="text-zinc-900 dark:text-white">Available Signal Tiers</flux:heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                        <flux:text size="xs" class="text-zinc-500 uppercase tracking-wide font-semibold">Price</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white mt-0.5">
                            ${{ number_format($tier->price, 2) }}
                        </flux:heading>
                    </div>

                    <flux:button variant="primary" class="w-full" icon="bolt" wire:click="openBuyModal({{ $tier->id }})">
                        Buy Signal
                    </flux:button>
                </flux:card>
            @empty
                <flux:card class="sm:col-span-2 lg:col-span-3 text-center py-12 text-zinc-500">
                    No signal tiers are available right now.
                </flux:card>
            @endforelse
        </div>
    </div>

    <flux:modal name="buy-signal-modal" class="max-w-md md:min-w-md" wire:model="showBuyModal">
        @if ($selectedTier)
            @php $allocation = (float) $selectedTier->price; @endphp
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
                        <flux:text class="font-semibold text-zinc-900 dark:text-white block">{{ $selectedTier->name }}</flux:text>
                        <flux:text size="sm" class="text-zinc-500">Deducted from your account balance</flux:text>
                    </div>
                    <flux:heading size="lg" class="font-mono text-zinc-900 dark:text-white">${{ number_format($allocation, 2) }}</flux:heading>
                </div>

                <flux:text size="sm" class="text-zinc-500">Your balance: <span class="font-mono text-zinc-900 dark:text-white">${{ number_format($balance, 2) }}</span></flux:text>

                @if ($balance < $allocation)
                    <flux:callout variant="warning" icon="exclamation-triangle">
                        Your balance is too low to buy this signal. Deposit more funds to continue.
                    </flux:callout>
                @endif

                <div class="flex gap-3">
                    <flux:button variant="outline" wire:click="closeBuyModal" class="flex-1">Cancel</flux:button>
                    <flux:button variant="primary" wire:click="buy" wire:loading.attr="disabled" wire:target="buy" class="flex-1" :disabled="$balance < $allocation">
                        Confirm Purchase
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
