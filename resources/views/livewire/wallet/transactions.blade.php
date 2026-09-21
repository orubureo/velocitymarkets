<div class="flex flex-col gap-6 stagger-children">
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Transaction History</flux:heading>
        <flux:text class="text-zinc-500">Every deposit, withdrawal and payout on your account.</flux:text>
    </div>

    @php
        // Icon shape tells you WHAT happened; color tells you the DIRECTION —
        // money in is always green, money out is always red, no exceptions.
        $typeIcon = fn (string $type) => match ($type) {
            'deposit' => 'arrow-down-tray',
            'withdrawal' => 'arrow-up-tray',
            'trade_profit' => 'arrow-trending-up',
            'trade_loss' => 'arrow-trending-down',
            'copy_trade_profit', 'copy_trade_loss', 'copy_trade_allocation' => 'sparkles',
            'referral_bonus', 'bonus' => 'gift',
            'roi_payout' => 'rocket-launch',
            'investment_purchase' => 'briefcase',
            'admin_adjustment' => 'adjustments-horizontal',
            default => 'banknotes',
        };

        $typeLabel = fn (string $type) => match ($type) {
            'roi_payout' => 'ROI Payout',
            'admin_adjustment' => 'Balance Adjustment',
            default => ucwords(str_replace('_', ' ', $type)),
        };

        $filterOptions = [
            'all' => 'All Types',
            'deposit' => 'Deposits',
            'withdrawal' => 'Withdrawals',
            'trade_profit' => 'Trade Profit',
            'trade_loss' => 'Trade Loss',
            'copy_trade_profit' => 'Copy Trade Profit',
            'copy_trade_loss' => 'Copy Trade Loss',
            'copy_trade_allocation' => 'Copy Trade Allocation',
            'referral_bonus' => 'Referral Bonus',
            'bonus' => 'Bonus',
            'roi_payout' => 'ROI Payout',
            'investment_purchase' => 'Investment Purchase',
            'admin_adjustment' => 'Balance Adjustment',
        ];
        $filterIcon = fn (string $value) => $value === 'all' ? 'funnel' : $typeIcon($value);
    @endphp

    @php
        $stepFn = 'let s=null,d=900;function step(ts){if(!s)s=ts;const p=Math.min((ts-s)/d,1);display=target*(1-Math.pow(1-p,3));if(p<1)requestAnimationFrame(step)}requestAnimationFrame(step)';
    @endphp

    {{-- Summary strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <flux:card class="trading-card group flex items-center gap-3">
            <div class="stat-icon-up !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="arrow-down-tray" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Total Deposits</flux:text>
                <div class="font-mono text-sm font-medium text-zinc-800 dark:text-white"
                    x-data="{ display: 0, target: {{ $totalDeposits }} }" x-init="{{ $stepFn }}"
                    x-text="'$' + display.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">$0.00</div>
            </div>
        </flux:card>

        <flux:card class="trading-card group flex items-center gap-3">
            <div class="stat-icon-down !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="arrow-up-tray" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Total Withdrawals</flux:text>
                <div class="font-mono text-sm font-medium text-zinc-800 dark:text-white"
                    x-data="{ display: 0, target: {{ $totalWithdrawals }} }" x-init="{{ $stepFn }}"
                    x-text="'$' + display.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">$0.00</div>
            </div>
        </flux:card>

        <flux:card class="trading-card group flex items-center gap-3">
            <div class="stat-icon-up !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="arrow-trending-up" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Total Profit</flux:text>
                <div class="font-mono text-sm font-medium text-zinc-800 dark:text-white"
                    x-data="{ display: 0, target: {{ $totalProfit }} }" x-init="{{ $stepFn }}"
                    x-text="'$' + display.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">$0.00</div>
            </div>
        </flux:card>
    </div>

    <flux:card class="trading-card !p-0 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
            <flux:heading size="sm">All Activity</flux:heading>

            <div x-data="{
                    open: false,
                    panelStyle: '',
                    openPanel() {
                        const r = this.$refs.trigger.getBoundingClientRect();
                        this.panelStyle = `top:${r.bottom + 8}px; left:${Math.min(r.left, window.innerWidth - 272)}px; width:${Math.max(r.width, 256)}px;`;
                        this.open = true;
                    }
                }" @keydown.escape.window="open = false">
                <button type="button" x-ref="trigger" @click="open ? (open = false) : openPanel()"
                    class="w-52 flex items-center justify-between gap-2 px-3 py-1.5 rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/10 text-sm text-zinc-700 dark:text-zinc-300 hover:border-teal-500/40 transition-colors">
                    <span class="flex items-center gap-2 truncate">
                        <flux:icon name="{{ $filterIcon($filter) }}" class="size-4 text-teal-500 shrink-0" />
                        <span class="truncate">{{ $filterOptions[$filter] ?? 'All Types' }}</span>
                    </span>
                    <flux:icon name="chevron-down" class="size-4 text-zinc-400 shrink-0 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                </button>

                <template x-teleport="body">
                    <div x-show="open" x-cloak x-transition @click.outside="open = false"
                        x-bind:style="panelStyle"
                        class="fixed z-30 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xl shadow-zinc-900/20 overflow-hidden">
                        <div class="max-h-80 overflow-y-auto py-1.5">
                            @foreach ($filterOptions as $value => $label)
                                <button type="button" wire:click="$set('filter', '{{ $value }}')" @click="open = false"
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-left text-sm transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800 {{ $filter === $value ? 'text-teal-600 dark:text-teal-400 bg-teal-500/5' : 'text-zinc-700 dark:text-zinc-300' }}">
                                    <flux:icon name="{{ $filterIcon($value) }}" class="size-4 shrink-0" />
                                    <span class="truncate">{{ $label }}</span>
                                    @if ($filter === $value)
                                        <flux:icon name="check" class="size-4 ml-auto shrink-0" />
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <flux:table>
            <flux:table.columns class="[&_th]:!py-4">
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Amount</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Note</flux:table.column>
                <flux:table.column>Date</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($transactions as $transaction)
                    @php
                        $isIn = $transaction->amount >= 0;
                        $icon = $typeIcon($transaction->type);
                        $iconClass = $isIn ? 'stat-icon-up' : 'stat-icon-down';
                    @endphp
                    <flux:table.row wire:key="txn-{{ $transaction->id }}"
                        x-data x-on:click="$dispatch('modal-show', { name: 'txn-{{ $transaction->id }}' })"
                        class="group cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <flux:table.cell class="!py-4 font-medium text-zinc-900 dark:text-white">
                            <div class="flex items-center gap-3">
                                <div class="{{ $iconClass }} !rounded-full !size-9 !p-0 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-110">
                                    <flux:icon name="{{ $icon }}" class="size-4" />
                                </div>
                                <span class="whitespace-nowrap">{{ $typeLabel($transaction->type) }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="!py-4">
                            <div class="flex items-center gap-1.5 font-mono font-semibold text-base {{ $isIn ? 'text-green-500' : 'text-red-500' }}">
                                {{ $isIn ? '+' : '-' }}${{ number_format(abs($transaction->amount), 2) }}
                                @if ($transaction->currency)
                                    <span class="text-[10px] font-sans font-semibold px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">{{ $transaction->currency }}</span>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="!py-4">
                            <x-status-badge :status="$transaction->status" />
                        </flux:table.cell>
                        <flux:table.cell class="!py-4 text-zinc-500">
                            <span class="block max-w-xs truncate">{{ $transaction->note }}</span>
                        </flux:table.cell>
                        <flux:table.cell class="!py-4 text-zinc-500 whitespace-nowrap">
                            <div class="flex items-center justify-between gap-2">
                                {{ $transaction->created_at->format('M j, Y') }}
                                <flux:icon name="chevron-right" class="size-4 text-zinc-300 dark:text-zinc-600 transition-transform duration-200 group-hover:translate-x-0.5" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5">
                            <div class="flex flex-col items-center justify-center py-12 text-center gap-2">
                                <div class="p-3 bg-zinc-50 dark:bg-zinc-900 rounded-full">
                                    <flux:icon name="document-text" class="size-6 text-zinc-300 dark:text-zinc-700" />
                                </div>
                                <flux:text class="text-zinc-500">No transactions yet.</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        <div class="px-6 py-4 border-t border-zinc-100 dark:border-zinc-800">
            {{ $transactions->links() }}
        </div>
    </flux:card>

    {{-- Detail modals --}}
    @foreach ($transactions as $transaction)
        @php
            $isIn = $transaction->amount >= 0;
            $icon = $typeIcon($transaction->type);
            $iconClass = $isIn ? 'stat-icon-up' : 'stat-icon-down';
        @endphp
        <flux:modal name="txn-{{ $transaction->id }}" class="max-w-md" wire:key="txn-modal-{{ $transaction->id }}">
            <div class="flex flex-col gap-5">
                <div class="flex items-center gap-3">
                    <div class="{{ $iconClass }} !rounded-full !size-11 !p-0 flex items-center justify-center shrink-0">
                        <flux:icon name="{{ $icon }}" class="size-5" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ $typeLabel($transaction->type) }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">Transaction #{{ $transaction->id }}</flux:text>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3.5">
                    <div>
                        <flux:text size="sm" class="text-zinc-500">Amount</flux:text>
                        <div class="flex items-center gap-2">
                            <div class="text-2xl font-bold font-mono {{ $isIn ? 'text-green-500' : 'text-red-500' }}">
                                {{ $isIn ? '+' : '-' }}${{ number_format(abs($transaction->amount), 2) }}
                            </div>
                            @if ($transaction->currency)
                                <span class="text-xs font-semibold px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300">{{ $transaction->currency }}</span>
                            @endif
                        </div>
                    </div>
                    <x-status-badge :status="$transaction->status" />
                </div>

                <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-800 overflow-hidden text-sm">
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-zinc-500">Transaction ID</span>
                        <div class="flex items-center gap-2" x-data="{ copied: false }">
                            <span class="font-mono text-zinc-900 dark:text-white">#{{ $transaction->id }}</span>
                            <button type="button" x-on:click.stop="navigator.clipboard.writeText('{{ $transaction->id }}'); copied = true; setTimeout(() => copied = false, 1500)"
                                class="text-zinc-400 hover:text-teal-500 transition-colors">
                                <flux:icon x-show="!copied" name="clipboard" class="size-3.5" />
                                <flux:icon x-show="copied" x-cloak name="check" class="size-3.5 text-teal-500" />
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-zinc-500">Date</span>
                        <span class="text-zinc-900 dark:text-white">{{ $transaction->created_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>
                    @if ($transaction->approved_at)
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-zinc-500">Reviewed</span>
                            <span class="text-zinc-900 dark:text-white">
                                {{ $transaction->approved_at->format('M j, Y \a\t g:i A') }}
                                @if ($transaction->approver)
                                    &middot; {{ $transaction->approver->name }}
                                @endif
                            </span>
                        </div>
                    @endif
                    @if ($transaction->note)
                        <div class="px-4 py-3">
                            <span class="text-zinc-500 block mb-1">Note</span>
                            <span class="text-zinc-900 dark:text-white">{{ $transaction->note }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
