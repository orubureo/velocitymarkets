<div class="flex flex-col gap-6 stagger-children">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <flux:heading size="xl">Crypto Portfolio</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Overview of your balance and active investments.</flux:text>
        </div>
        <flux:button variant="outline" icon="arrow-left" :href="route('trade')" wire:navigate>Back to Trading</flux:button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total Value</span>
                <div class="stat-icon-brand !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="arrow-trending-up" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-white font-mono">${{ number_format($totalValue, 2) }}</h2>
        </flux:card>

        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total Invested</span>
                <div class="stat-icon-sky !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="currency-dollar" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-white font-mono">${{ number_format($totalInvested, 2) }}</h2>
        </flux:card>

        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total P/L</span>
                <div class="{{ $totalPnl >= 0 ? 'stat-icon-up' : 'stat-icon-down' }} !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="{{ $totalPnl >= 0 ? 'arrow-up' : 'arrow-down' }}" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold font-mono {{ $totalPnl >= 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ $totalPnl >= 0 ? '+' : '-' }}${{ number_format(abs($totalPnl), 2) }}
            </h2>
        </flux:card>

        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Active Investments</span>
                <div class="stat-icon-violet !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="briefcase" class="size-4" />
                </div>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-white font-mono">{{ $holdingsCount }}</h2>
        </flux:card>
    </div>

    {{-- Investments --}}
    <flux:card class="trading-card !p-0 overflow-hidden">
        <div class="flex items-center gap-6 border-b border-zinc-200 dark:border-zinc-700 px-5 pt-4">
            <button type="button" wire:click="$set('tab', 'active')"
                class="text-sm font-semibold pb-3 -mb-px cursor-pointer transition-colors {{ $tab === 'active' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                Active ({{ $activeInvestments->count() }})
            </button>
            <button type="button" wire:click="$set('tab', 'completed')"
                class="text-sm font-semibold pb-3 -mb-px cursor-pointer transition-colors {{ $tab === 'completed' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                Completed ({{ $completedInvestments->count() }})
            </button>
        </div>

        <div class="p-5">
            @php $investments = $tab === 'active' ? $activeInvestments : $completedInvestments; @endphp

            @if ($investments->isEmpty())
                <div class="flex flex-col items-center gap-3 text-center py-14">
                    <flux:icon name="inbox" class="size-8 text-zinc-300 dark:text-zinc-700" />
                    <flux:heading size="md">No {{ $tab }} investments yet</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Browse our investment plans and start growing your portfolio.</flux:text>
                    <flux:button variant="primary" :href="route('investment.plans')" wire:navigate class="mt-2">
                        Browse Plans
                    </flux:button>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($investments as $investment)
                        <flux:card wire:key="portfolio-investment-{{ $investment->id }}"
                            class="trading-card flex flex-col gap-4 relative overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="flex items-start justify-between relative z-10">
                                <div>
                                    <flux:badge color="{{ $investment->isActive() ? 'lime' : 'zinc' }}" size="sm" class="mb-2">
                                        {{ ucfirst($investment->status) }}
                                    </flux:badge>
                                    <flux:heading size="lg">{{ $investment->plan->name }}</flux:heading>
                                </div>
                                <div class="stat-icon-violet !rounded-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12">
                                    <flux:icon name="rocket-launch" class="size-5" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-2 relative z-10">
                                <div>
                                    <flux:text size="sm" class="text-zinc-500">Invested</flux:text>
                                    <flux:heading size="md" class="font-mono text-zinc-900 dark:text-white">
                                        ${{ number_format($investment->amount, 2) }}</flux:heading>
                                </div>
                                <div>
                                    <flux:text size="sm" class="text-zinc-500">ROI Paid</flux:text>
                                    <flux:heading size="md" class="font-mono text-green-500">
                                        ${{ number_format($investment->totalRoiPaid(), 2) }}</flux:heading>
                                </div>
                            </div>

                            @if ($investment->isActive())
                                <div class="mt-2 relative z-10">
                                    <div class="flex justify-between text-xs text-zinc-500 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $investment->daysRemaining() }} Days Left</span>
                                    </div>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-violet-500 to-violet-400 h-1.5 rounded-full"
                                            style="width: {{ $investment->progressPercent() }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </flux:card>
                    @endforeach
                </div>
            @endif
        </div>
    </flux:card>
</div>
