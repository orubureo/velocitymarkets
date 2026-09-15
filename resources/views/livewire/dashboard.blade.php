<div class="flex flex-col gap-6 stagger-children">

    {{-- Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Welcome back,
                {{ explode(' ', auth()->user()->name)[0] }}</flux:heading>
            <flux:text class="text-zinc-500">Here's an overview of your account.</flux:text>
        </div>
        <div class="flex items-center gap-2">
            <span class="hidden sm:inline text-xs font-mono text-zinc-400 dark:text-zinc-500">{{ now()->format('M j, Y') }} &middot; {{ now()->format('h:i A') }}</span>
        </div>
    </div>

    {{-- Account Balance --}}
    <div x-data="{ hidden: $persist(false).as('balance-hidden') }"
        class="relative rounded-3xl bg-gradient-to-br from-zinc-900 via-zinc-900 to-black p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 overflow-hidden text-white shadow-xl shadow-zinc-900/10">
        <div class="absolute -top-24 -right-16 size-72 bg-teal-500/20 rounded-full blur-3xl pointer-events-none animate-blob-drift"></div>
        <div class="absolute -bottom-32 -left-16 size-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none animate-blob-drift-slow"></div>
        <div class="absolute inset-0 opacity-[0.04] [background-image:radial-gradient(circle_at_1px_1px,white_1px,transparent_0)] [background-size:18px_18px] pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2.5">
                <div class="p-2 rounded-xl bg-white/10">
                    <flux:icon name="wallet" variant="outline" class="size-5 text-teal-400" />
                </div>
                <h3 class="text-lg font-semibold text-white">Account Balance</h3>
                <button type="button" x-on:click="hidden = !hidden"
                    x-bind:title="hidden ? 'Show balance' : 'Hide balance'"
                    class="text-zinc-400 hover:text-white transition-colors">
                    <flux:icon x-show="!hidden" name="eye" class="size-4" />
                    <flux:icon x-show="hidden" name="eye-slash" class="size-4" x-cloak />
                </button>
            </div>

            <h1 class="mt-3 text-4xl lg:text-5xl font-bold tracking-tight">
                <span x-show="!hidden">$ {{ number_format($accountBalance, 2) }}</span>
                <span x-show="hidden" x-cloak>$ &bull;&bull;&bull;&bull;&bull;&bull;</span>
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-500/15 text-teal-400 text-sm font-medium">
                    <flux:icon name="check-circle" variant="outline" class="size-4" />
                    Available for Withdrawal
                </div>
                <span class="text-xs text-zinc-500 font-mono">Updated {{ now()->format('M j, Y h:i A') }}</span>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="grid grid-cols-3 gap-3 relative z-10 w-full lg:w-auto lg:min-w-[380px]">
            <a href="{{ route('wallet', ['tab' => 'deposit']) }}" wire:navigate
                class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white hover:bg-zinc-100 text-zinc-900 font-semibold text-sm transition-all duration-200 hover:scale-[1.02] active:scale-95">
                <flux:icon name="plus-circle" variant="outline" class="size-4" />
                Deposit
            </a>
            <a href="{{ route('wallet', ['tab' => 'withdraw']) }}" wire:navigate
                class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-white font-semibold text-sm transition-all duration-200 hover:scale-[1.02] active:scale-95">
                <flux:icon name="arrow-up-right" variant="outline" class="size-4" />
                Withdraw
            </a>
            <a href="{{ route('investment.plans') }}" wire:navigate
                class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/20 text-amber-400 font-semibold text-sm transition-all duration-200 hover:scale-[1.02] active:scale-95">
                <flux:icon name="rocket-launch" variant="outline" class="size-4" />
                Invest
            </a>
        </div>
    </div>

    {{-- Portfolio Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Bonus --}}
        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Bonus</span>
                <div class="stat-icon-amber !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="gift" class="size-4" />
                </div>
            </div>
            <div class="mt-4 mb-4">
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">$
                    {{ number_format($referralBonus, 2) }}</h2>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400 font-medium">
                <flux:icon name="star" variant="solid" class="size-3" /> Rewards & Promotions
            </div>
        </flux:card>

        {{-- Total Profit --}}
        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total Profit</span>
                <div class="stat-icon-up !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="arrow-trending-up" class="size-4" />
                </div>
            </div>
            <div class="mt-4 mb-4">
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">$
                    {{ number_format($totalProfit, 2) }}</h2>
            </div>
            <div class="flex items-center gap-1.5 text-xs">
                @if ($totalProfit > 0)
                    <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-500 font-medium">
                        <flux:icon name="arrow-up" class="size-3" /> Earning
                    </span>
                @else
                    <span class="text-zinc-400">No change yet</span>
                @endif
            </div>
        </flux:card>

        {{-- Total Withdrawal --}}
        <flux:card class="trading-card group flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total Withdrawal</span>
                <div class="stat-icon-violet !rounded-full !size-9 flex items-center justify-center !p-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="arrow-up" class="size-4" />
                </div>
            </div>
            <div class="mt-4 mb-4">
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white font-mono">$
                    {{ number_format($totalWithdrawal, 2) }}</h2>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-zinc-500">
                <flux:icon name="calendar" variant="outline" class="size-3.5" /> All time
            </div>
        </flux:card>
    </div>

    {{-- Trading Activity --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Open Trades --}}
        <flux:card class="trading-card group flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="chart-bar-square" class="size-5" />
                    </div>
                    <div>
                        <flux:heading size="sm">Open Trades</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">
                            {{ $openTrades->count() }} active
                            position{{ $openTrades->count() === 1 ? '' : 's' }} &middot; $
                            {{ number_format($openTradesStake, 2) }} staked
                        </flux:text>
                    </div>
                </div>
                <flux:link :href="route('trade')" wire:navigate class="group/link inline-flex items-center gap-1 text-sm font-medium text-teal-500 shrink-0">
                    Trade <flux:icon name="arrow-right" class="size-3.5 transition-transform duration-200 group-hover/link:translate-x-0.5" />
                </flux:link>
            </div>

            <div class="flex flex-col">
                @forelse ($openTrades->take(3) as $trade)
                    <div
                        class="flex items-center justify-between py-2.5 px-2 -mx-2 rounded-lg transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ ! $loop->last ? 'border-b border-zinc-100 dark:border-zinc-800' : '' }}">
                        <div class="flex items-center gap-2.5">
                            <flux:badge size="sm" color="{{ $trade->direction === 'rise' ? 'lime' : 'red' }}">
                                {{ strtoupper($trade->direction) }}
                            </flux:badge>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $trade->asset }}</span>
                        </div>
                        <span class="text-sm font-mono text-zinc-500">${{ number_format($trade->stake, 2) }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center gap-2 py-6 text-center">
                        <flux:icon name="chart-bar-square" class="size-6 text-zinc-300 dark:text-zinc-700" />
                        <flux:text size="sm" class="text-zinc-500">No open trades right now.</flux:text>
                    </div>
                @endforelse
            </div>
        </flux:card>

        {{-- Copy Trading --}}
        <flux:card class="trading-card group flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-cyan-500/10 text-cyan-500 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="sparkles" class="size-5" />
                    </div>
                    <div>
                        <flux:heading size="sm">Copy Trading</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">
                            {{ $activeCopySubscriptions->count() }}
                            trader{{ $activeCopySubscriptions->count() === 1 ? '' : 's' }} copied
                            @if ($activeCopySubscriptions->isNotEmpty())
                                &middot; <span
                                    class="{{ $copyNetPnl >= 0 ? 'text-green-500' : 'text-red-500' }}">{{ $copyNetPnl >= 0 ? '+' : '-' }}${{ number_format(abs($copyNetPnl), 2) }}</span>
                                net
                            @endif
                        </flux:text>
                    </div>
                </div>
                <flux:link :href="route('copy-trading')" wire:navigate class="group/link inline-flex items-center gap-1 text-sm font-medium text-cyan-500 shrink-0">
                    Explore <flux:icon name="arrow-right" class="size-3.5 transition-transform duration-200 group-hover/link:translate-x-0.5" />
                </flux:link>
            </div>

            <div class="flex flex-col">
                @forelse ($activeCopySubscriptions->take(3) as $sub)
                    <div
                        class="flex items-center justify-between py-2.5 px-2 -mx-2 rounded-lg transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ ! $loop->last ? 'border-b border-zinc-100 dark:border-zinc-800' : '' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="size-6 rounded-full bg-cyan-500/10 flex items-center justify-center text-[10px] font-semibold text-cyan-600 dark:text-cyan-400 shrink-0">
                                {{ strtoupper(substr($sub->trader->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $sub->trader->name }}</span>
                        </div>
                        <span class="text-sm font-mono {{ $sub->netPnl() >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $sub->netPnl() >= 0 ? '+' : '-' }}${{ number_format(abs($sub->netPnl()), 2) }}
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center gap-2 py-6 text-center">
                        <flux:icon name="sparkles" class="size-6 text-zinc-300 dark:text-zinc-700" />
                        <flux:text size="sm" class="text-zinc-500">You're not copying any traders yet.</flux:text>
                    </div>
                @endforelse
            </div>
        </flux:card>
    </div>

    {{-- Investment Plans --}}
    <div class="flex flex-col gap-4 mt-2">
        <div class="flex items-center justify-between">
            <flux:heading size="lg" class="text-zinc-900 dark:text-white">Active Investment Plans</flux:heading>
            <flux:link :href="route('investment.plans')" wire:navigate class="group/link inline-flex items-center gap-1 text-sm font-medium text-violet-500">
                View All Plans <flux:icon name="arrow-right" class="size-3.5 transition-transform duration-200 group-hover/link:translate-x-0.5" />
            </flux:link>
        </div>

        @if ($activeInvestments->isEmpty())
            <flux:card
                class="flex flex-col items-center justify-center gap-3 border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/50 text-center py-10">
                <div
                    class="p-3 bg-white dark:bg-zinc-950 rounded-full shadow-sm border border-zinc-200 dark:border-zinc-800 mb-2">
                    <flux:icon name="briefcase" class="size-6 text-zinc-400" />
                </div>
                <flux:heading size="md">No active investments yet</flux:heading>
                <flux:text size="sm" class="text-zinc-500">Browse our investment plans and start growing your portfolio.
                </flux:text>
                <flux:button variant="primary" :href="route('investment.plans')" wire:navigate class="mt-2">
                    Browse Plans
                </flux:button>
            </flux:card>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($activeInvestments as $investment)
                    <flux:card wire:key="dash-investment-{{ $investment->id }}"
                        class="trading-card flex flex-col gap-4 relative overflow-hidden group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="flex items-start justify-between relative z-10">
                            <div>
                                <flux:badge color="lime" size="sm" class="mb-2">Active</flux:badge>
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

                        <div class="mt-2 relative z-10">
                            <div class="flex justify-between text-xs text-zinc-500 mb-1">
                                <span>Progress</span>
                                <span>{{ $investment->daysRemaining() }} Days Left</span>
                            </div>
                            <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-1.5">
                                <div class="bg-gradient-to-r from-violet-500 to-violet-400 h-1.5 rounded-full animate-fill-bar"
                                    style="width: {{ $investment->progressPercent() }}%"></div>
                            </div>
                        </div>
                    </flux:card>
                @endforeach

                {{-- Explore Plans Call to Action --}}
                <a href="{{ route('investment.plans') }}" wire:navigate class="block">
                    <flux:card
                        class="flex flex-col items-center justify-center gap-3 h-full border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/50 text-center cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                        <div
                            class="p-3 bg-white dark:bg-zinc-950 rounded-full shadow-sm border border-zinc-200 dark:border-zinc-800 mb-2">
                            <flux:icon name="plus" class="size-6 text-zinc-400" />
                        </div>
                        <flux:heading size="md">Explore More Plans</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">Find a plan that suits your goals.</flux:text>
                    </flux:card>
                </a>
            </div>
        @endif
    </div>

    {{-- Referral Link --}}
    <flux:card class="trading-card group flex flex-col gap-3 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 size-40 bg-teal-500/5 rounded-full blur-2xl pointer-events-none animate-blob-drift"></div>
        <div class="flex items-center gap-2.5 relative z-10">
            <div class="stat-icon-brand !rounded-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="user-group" class="size-5" />
            </div>
            <flux:heading size="sm">Your Referral Link</flux:heading>
        </div>
        <div class="flex gap-2 relative z-10" x-data="{ copied: false }">
            <flux:input readonly value="{{ $referralLink }}" class="font-mono text-sm bg-zinc-50 dark:bg-zinc-950" />
            <flux:button
                x-on:click="navigator.clipboard.writeText('{{ $referralLink }}'); copied = true; setTimeout(() => copied = false, 2000)"
                icon="clipboard" variant="primary">
                <span x-show="!copied">Copy</span>
                <span x-show="copied" x-cloak>Copied!</span>
            </flux:button>
        </div>
    </flux:card>

    {{-- Recent Transactions --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <flux:heading size="lg" class="text-zinc-900 dark:text-white">Recent Transactions</flux:heading>
            <flux:link :href="route('transactions')" wire:navigate class="group/link inline-flex items-center gap-1 text-sm font-medium text-teal-500">
                View all <flux:icon name="arrow-right" class="size-3.5 transition-transform duration-200 group-hover/link:translate-x-0.5" />
            </flux:link>
        </div>

        <flux:card class="trading-card !p-0 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Type</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($recentTransactions as $transaction)
                        <flux:table.row wire:key="recent-txn-{{ $transaction->id }}"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <flux:table.cell class="capitalize font-medium text-zinc-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="p-1.5 rounded-full {{ $transaction->amount >= 0 ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-500' : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-500' }}">
                                        <flux:icon
                                            name="{{ $transaction->amount >= 0 ? 'arrow-down-left' : 'arrow-up-right' }}"
                                            class="size-3" />
                                    </div>
                                    {{ str_replace('_', ' ', $transaction->type) }}
                                </div>
                            </flux:table.cell>
                            <flux:table.cell
                                class="font-mono {{ $transaction->amount >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                <div class="flex items-center gap-1.5">
                                    {{ $transaction->amount >= 0 ? '+' : '-' }}${{ number_format(abs($transaction->amount), 2) }}
                                    @if ($transaction->currency)
                                        <span class="text-[10px] font-sans font-semibold px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">{{ $transaction->currency }}</span>
                                    @endif
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <x-status-badge :status="$transaction->status" />
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500">{{ $transaction->created_at->format('M j, Y') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center text-zinc-500 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <flux:icon name="document-text" class="size-8 text-zinc-400 mb-2" />
                                    <span>No transactions yet.</span>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>

</div>
