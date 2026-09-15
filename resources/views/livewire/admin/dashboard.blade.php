<div>
    <div class="flex flex-col gap-6 stagger-children">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">System Overview</flux:heading>
                <flux:text class="text-zinc-500">Welcome back to the Admin control panel.</flux:text>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:card class="trading-card relative overflow-hidden flex flex-col gap-3 bg-gradient-to-br from-zinc-50 to-white dark:from-zinc-900 dark:to-zinc-950">
                <div class="flex items-center justify-between">
                    <flux:text size="sm" class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Pending Deposits</flux:text>
                    <div class="stat-icon-brand"><flux:icon name="banknotes" class="size-5" /></div>
                </div>
                <div class="text-3xl font-bold font-mono tabular-nums">{{ $pendingDeposits }}</div>
            </flux:card>

            <flux:card class="trading-card relative overflow-hidden flex flex-col gap-3 bg-gradient-to-br from-zinc-50 to-white dark:from-zinc-900 dark:to-zinc-950">
                <div class="flex items-center justify-between">
                    <flux:text size="sm" class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Pending Withdrawals</flux:text>
                    <div class="stat-icon-down"><flux:icon name="arrow-up-tray" class="size-5" /></div>
                </div>
                <div class="text-3xl font-bold font-mono tabular-nums">{{ $pendingWithdrawals }}</div>
            </flux:card>

            <flux:card class="trading-card relative overflow-hidden flex flex-col gap-3 bg-gradient-to-br from-zinc-50 to-white dark:from-zinc-900 dark:to-zinc-950">
                <div class="flex items-center justify-between">
                    <flux:text size="sm" class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Total Users</flux:text>
                    <div class="stat-icon-up"><flux:icon name="users" class="size-5" /></div>
                </div>
                <div class="text-3xl font-bold font-mono tabular-nums">{{ number_format($totalUsers) }}</div>
                <div class="inline-flex items-center gap-1 w-fit px-2 py-0.5 rounded-full text-xs font-semibold bg-green-500/10 text-green-600 dark:text-green-400">
                    <flux:icon name="arrow-trending-up" class="size-3" />
                    +{{ $newUsersThisWeek }} this week
                </div>
            </flux:card>

            <flux:card class="trading-card relative overflow-hidden flex flex-col gap-3 bg-gradient-to-br from-zinc-50 to-white dark:from-zinc-900 dark:to-zinc-950">
                <div class="flex items-center justify-between">
                    <flux:text size="sm" class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Transaction Volume (24h)</flux:text>
                    <div class="stat-icon-brand"><flux:icon name="chart-bar" class="size-5" /></div>
                </div>
                <div class="text-3xl font-bold font-mono tabular-nums">${{ number_format($volume24h, 2) }}</div>
                @if (! is_null($volumeChangePercent))
                    <div class="inline-flex items-center gap-1 w-fit px-2 py-0.5 rounded-full text-xs font-semibold {{ $volumeChangePercent >= 0 ? 'bg-green-500/10 text-green-600 dark:text-green-400' : 'bg-red-500/10 text-red-600 dark:text-red-400' }}">
                        <flux:icon name="{{ $volumeChangePercent >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-3" />
                        {{ $volumeChangePercent >= 0 ? '+' : '' }}{{ $volumeChangePercent }}% vs previous 24h
                    </div>
                @else
                    <flux:text size="sm" class="text-zinc-500">No prior data</flux:text>
                @endif
            </flux:card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- User Registrations chart --}}
            <div class="lg:col-span-2 min-w-0">
                <flux:card class="trading-card h-full">
                    <div class="flex items-start justify-between gap-4 mb-1">
                        <div>
                            <flux:heading size="lg">User Registrations</flux:heading>
                            <flux:text size="sm" class="text-zinc-500">
                                New sign-ups per day, last {{ $windowDays }} days
                                @if ($peakCount > 0)
                                    &middot; peak {{ $peakCount }} on {{ $peakLabel }}
                                @endif
                            </flux:text>
                        </div>
                        <div class="text-right shrink-0">
                            <flux:heading size="lg" class="font-mono">{{ number_format($signupsCurrentTotal) }}</flux:heading>
                            @if (! is_null($signupsChangePercent))
                                <flux:text size="sm" class="{{ $signupsChangePercent >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $signupsChangePercent >= 0 ? '+' : '' }}{{ $signupsChangePercent }}% vs prior {{ $windowDays }}d
                                </flux:text>
                            @else
                                <flux:text size="sm" class="text-zinc-500">No prior data</flux:text>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 min-w-0">
                        <x-admin.bar-chart
                            uid="reg"
                            :labels="$chartLabels"
                            :short-labels="$chartShortLabels"
                            :series="[
                                ['key' => 'signups', 'label' => 'Sign-ups', 'values' => $registrations, 'direction' => 'up', 'fillClass' => 'text-accent', 'dotClass' => 'bg-accent'],
                            ]"
                            :trend="$registrationsAvg7"
                            mode="baseline"
                            format="int"
                            height="h-40 sm:h-44"
                            empty-icon="chart-bar"
                            empty-message="No sign-ups in the last {{ $windowDays }} days."
                            table-caption="Daily user registrations for the last {{ $windowDays }} days"
                            :summary="$peakCount > 0
                                ? number_format($signupsCurrentTotal).' sign-ups over the last '.$windowDays.' days, peaking at '.$peakCount.' on '.$peakLabel.'.'
                                : 'No sign-ups in the last '.$windowDays.' days.'"
                        />
                    </div>
                </flux:card>
            </div>

            {{-- Alerts --}}
            <div class="min-w-0">
                <flux:card class="trading-card flex flex-col gap-4">
                    <flux:heading size="lg">Alerts</flux:heading>

                    <div class="flex flex-col gap-3">
                        @if ($pendingDeposits > 0)
                            <a href="{{ route('admin.deposits') }}" wire:navigate class="flex items-start gap-3 -mx-2 px-2 py-1 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="mt-0.5 rounded-full bg-sky-100 dark:bg-sky-900/30 p-1.5 text-sky-600 dark:text-sky-400">
                                    <flux:icon name="exclamation-triangle" class="size-4" />
                                </div>
                                <div>
                                    <flux:heading size="sm">{{ $pendingDeposits }} deposit{{ $pendingDeposits === 1 ? '' : 's' }} awaiting review</flux:heading>
                                    <flux:text size="sm" class="text-zinc-500">Confirm payment before crediting user wallets.</flux:text>
                                </div>
                            </a>
                        @endif

                        @if ($pendingWithdrawals > 0)
                            <a href="{{ route('admin.withdrawals') }}" wire:navigate class="flex items-start gap-3 -mx-2 px-2 py-1 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="mt-0.5 rounded-full bg-red-100 dark:bg-red-900/30 p-1.5 text-red-600 dark:text-red-400">
                                    <flux:icon name="arrow-up-tray" class="size-4" />
                                </div>
                                <div>
                                    <flux:heading size="sm">{{ $pendingWithdrawals }} withdrawal{{ $pendingWithdrawals === 1 ? '' : 's' }} awaiting review</flux:heading>
                                    <flux:text size="sm" class="text-zinc-500">Verify wallet balance before approving.</flux:text>
                                </div>
                            </a>
                        @endif

                        @if ($openTickets > 0)
                            <a href="{{ route('admin.support-tickets') }}" wire:navigate class="flex items-start gap-3 -mx-2 px-2 py-1 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="mt-0.5 rounded-full bg-violet-100 dark:bg-violet-900/30 p-1.5 text-violet-600 dark:text-violet-400">
                                    <flux:icon name="lifebuoy" class="size-4" />
                                </div>
                                <div>
                                    <flux:heading size="sm">{{ $openTickets }} ticket{{ $openTickets === 1 ? '' : 's' }} need{{ $openTickets === 1 ? 's' : '' }} a response</flux:heading>
                                    <flux:text size="sm" class="text-zinc-500">Customers are waiting on a reply.</flux:text>
                                </div>
                            </a>
                        @endif

                        @if ($pendingDeposits === 0 && $pendingWithdrawals === 0 && $openTickets === 0)
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 rounded-full bg-green-100 dark:bg-green-900/30 p-1.5 text-green-600 dark:text-green-400">
                                    <flux:icon name="check-circle" class="size-4" />
                                </div>
                                <div>
                                    <flux:heading size="sm">All caught up</flux:heading>
                                    <flux:text size="sm" class="text-zinc-500">No pending deposits, withdrawals, or tickets.</flux:text>
                                </div>
                            </div>
                        @endif
                    </div>
                </flux:card>
            </div>
        </div>

        {{-- Transaction Overview chart --}}
        <flux:card class="trading-card">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-1">
                <div>
                    <flux:heading size="lg">Transaction Overview</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Settled deposits vs withdrawals, last {{ $windowDays }} days</flux:text>

                    <div class="flex items-center gap-4 mt-2">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block size-2 rounded-full bg-accent"></span>
                            <flux:text size="xs" class="text-zinc-500">Deposits in</flux:text>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block size-2 rounded-full bg-red-500"></span>
                            <flux:text size="xs" class="text-zinc-500">Withdrawals out</flux:text>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block w-3 h-0.5 border-t border-dashed border-zinc-400"></span>
                            <flux:text size="xs" class="text-zinc-500">7-day net average</flux:text>
                        </div>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <flux:text size="sm" class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Net Flow</flux:text>
                    <flux:heading size="lg" class="font-mono {{ $netFlow >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ $netFlow >= 0 ? '+' : '-' }}${{ number_format(abs($netFlow), 2) }}
                    </flux:heading>
                    @if (! is_null($netFlowChangePercent))
                        <flux:text size="sm" class="{{ $netFlowChangePercent >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $netFlowChangePercent >= 0 ? '+' : '' }}{{ $netFlowChangePercent }}% vs prior {{ $windowDays }}d
                        </flux:text>
                    @else
                        <flux:text size="sm" class="text-zinc-500">No prior data</flux:text>
                    @endif
                </div>
            </div>

            <div class="mt-4 min-w-0">
                <x-admin.bar-chart
                    uid="tx"
                    :labels="$chartLabels"
                    :short-labels="$chartShortLabels"
                    :series="[
                        ['key' => 'in', 'label' => 'Deposits in', 'values' => $depositsIn, 'direction' => 'up', 'fillClass' => 'text-accent', 'dotClass' => 'bg-accent'],
                        ['key' => 'out', 'label' => 'Withdrawals out', 'values' => $withdrawalsOut, 'direction' => 'down', 'fillClass' => 'text-red-500 dark:text-red-400', 'dotClass' => 'bg-red-500'],
                    ]"
                    :trend="$netFlowAvg7"
                    mode="diverging"
                    format="currency"
                    height="h-48 sm:h-56"
                    empty-icon="arrows-right-left"
                    empty-message="No settled deposits or withdrawals in the last {{ $windowDays }} days."
                    table-caption="Daily settled deposits and withdrawals for the last {{ $windowDays }} days"
                    :summary="'Over the last '.$windowDays.' days, $'.number_format(array_sum($depositsIn), 2).' was deposited and $'.number_format(array_sum($withdrawalsOut), 2).' withdrawn — a net '.($netFlow >= 0 ? 'inflow' : 'outflow').' of $'.number_format(abs($netFlow), 2).'.'"
                />
            </div>
        </flux:card>
    </div>
</div>
