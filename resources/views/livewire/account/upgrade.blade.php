<div class="flex flex-col gap-8 stagger-children">
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Account Upgrade</flux:heading>
        <flux:text class="text-zinc-500">Unlock higher daily returns and bigger referral bonuses.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    {{-- Current plan --}}
    <flux:card class="trading-card flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="stat-icon-brand !rounded-xl">
                <flux:icon name="star" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Current Plan</flux:text>
                <flux:heading size="md" class="text-zinc-900 dark:text-white">{{ $currentTier->name ?? 'Free Account' }}</flux:heading>
            </div>
        </div>
        <div class="text-right">
            <flux:text size="sm" class="text-zinc-500">Available Balance</flux:text>
            <flux:heading size="md" class="font-mono text-zinc-900 dark:text-white">${{ number_format($balance, 2) }}</flux:heading>
        </div>
    </flux:card>

    {{-- Available Upgrade Plans --}}
    <div class="flex flex-col gap-4">
        <flux:heading size="lg" class="text-zinc-900 dark:text-white">Available Upgrade Plans</flux:heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($tiers as $tier)
                @php $isCurrent = $tier->id === $currentTierId; @endphp
                <flux:card wire:key="tier-{{ $tier->id }}" class="trading-card flex flex-col gap-4 {{ $isCurrent ? '!border-teal-500' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <flux:heading size="lg">{{ $tier->name }}</flux:heading>
                            <flux:text size="sm" class="text-zinc-500">{{ $tier->description ?: 'Upgrade your account level' }}</flux:text>
                        </div>
                        @if ($isCurrent)
                            <flux:badge size="sm" color="lime">Current</flux:badge>
                        @endif
                    </div>

                    <div>
                        <flux:text size="sm" class="text-zinc-500">Price</flux:text>
                        <flux:heading size="xl" class="font-mono text-zinc-900 dark:text-white">${{ number_format($tier->price, 2) }}</flux:heading>
                    </div>

                    <div class="flex flex-col gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Daily Profit</span>
                            <span class="font-mono font-semibold text-green-500">{{ $tier->daily_profit_percent }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Total Return</span>
                            <span class="font-mono font-semibold text-zinc-900 dark:text-white">{{ $tier->total_return_percent }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Referral Bonus</span>
                            <span class="font-mono font-semibold text-zinc-900 dark:text-white">{{ $tier->referral_bonus_percent }}%</span>
                        </div>
                    </div>

                    @if ($isCurrent)
                        <flux:button variant="outline" class="w-full" disabled>Current Plan</flux:button>
                    @else
                        <flux:button variant="primary" class="w-full" wire:click="openUpgradeModal({{ $tier->id }})">
                            Select Plan
                        </flux:button>
                    @endif
                </flux:card>
            @empty
                <flux:card class="sm:col-span-2 lg:col-span-3 text-center py-12 text-zinc-500">
                    No upgrade plans are available right now.
                </flux:card>
            @endforelse
        </div>
    </div>

    <flux:modal name="upgrade-modal" class="max-w-md md:min-w-md" wire:model="showUpgradeModal">
        @if ($selectedTier)
            <div class="flex flex-col gap-5">
                <div>
                    <flux:heading size="lg">Upgrade to {{ $selectedTier->name }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Confirm your account upgrade below.</flux:text>
                </div>

                <div class="grid grid-cols-3 gap-2 p-3 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl">
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Daily Profit</flux:text>
                        <flux:heading size="sm" class="font-mono text-green-500">{{ $selectedTier->daily_profit_percent }}%</flux:heading>
                    </div>
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Total Return</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">{{ $selectedTier->total_return_percent }}%</flux:heading>
                    </div>
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Referral Bonus</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">{{ $selectedTier->referral_bonus_percent }}%</flux:heading>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl border-2 border-teal-500 bg-teal-500/5">
                    <div>
                        <flux:text class="font-semibold text-zinc-900 dark:text-white block">Plan Price</flux:text>
                        <flux:text size="sm" class="text-zinc-500">Charged to your account balance</flux:text>
                    </div>
                    <flux:heading size="lg" class="font-mono text-zinc-900 dark:text-white">${{ number_format($selectedTier->price, 2) }}</flux:heading>
                </div>

                <flux:text size="sm" class="text-zinc-500">Your balance: <span class="font-mono text-zinc-900 dark:text-white">${{ number_format($balance, 2) }}</span></flux:text>

                @if ($balance < $selectedTier->price)
                    <flux:callout variant="warning" icon="exclamation-triangle">
                        Your balance is too low for this plan. Deposit more funds to continue.
                    </flux:callout>
                @endif

                <div class="flex gap-3">
                    <flux:button variant="outline" wire:click="closeUpgradeModal" class="flex-1">Cancel</flux:button>
                    <flux:button variant="primary" wire:click="upgrade" class="flex-1" :disabled="$balance < $selectedTier->price">
                        Confirm Upgrade
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
