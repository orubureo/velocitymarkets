<div class="flex flex-col gap-8 stagger-children">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Copy Trading</flux:heading>
            <flux:text class="text-zinc-500">Allocate funds to follow the strategy of top-performing traders.</flux:text>
        </div>
        <flux:button variant="outline" icon="queue-list" :href="route('copy-trading.subscriptions')" wire:navigate>
            My Subscriptions
        </flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    @php
        $sparkPaths = [
            '0,22 15,20 30,16 45,18 60,10 75,12 100,4',
            '0,20 15,22 30,14 45,16 60,8 75,10 100,3',
            '0,24 15,18 30,20 45,12 60,14 75,6 100,8',
            '0,18 15,20 30,12 45,14 60,6 75,8 100,2',
        ];
        $stepFn = 'let s=null,d=900;function step(ts){if(!s)s=ts;const p=Math.min((ts-s)/d,1);display=target*(1-Math.pow(1-p,3));if(p<1)requestAnimationFrame(step)}requestAnimationFrame(step)';
    @endphp

    {{-- Summary strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <flux:card class="trading-card group flex items-center gap-3">
            <div class="stat-icon-brand !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="sparkles" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Traders Copied</flux:text>
                <div class="font-mono text-2xl font-bold text-zinc-900 dark:text-white"
                    x-data="{ display: 0, target: {{ $activeCount }} }" x-init="{{ $stepFn }}"
                    x-text="Math.round(display).toLocaleString('en-US')">0</div>
            </div>
        </flux:card>

        <flux:card class="trading-card group flex items-center gap-3">
            <div class="stat-icon-violet !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="banknotes" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Total Allocated</flux:text>
                <div class="font-mono text-2xl font-bold text-zinc-900 dark:text-white"
                    x-data="{ display: 0, target: {{ $totalAllocated }} }" x-init="{{ $stepFn }}"
                    x-text="'$' + display.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">$0.00</div>
            </div>
        </flux:card>

        <flux:card class="trading-card group flex items-center gap-3">
            <div class="{{ $netPnl >= 0 ? 'stat-icon-up' : 'stat-icon-down' }} !rounded-xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <flux:icon name="{{ $netPnl >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Net P&amp;L</flux:text>
                <div class="font-mono text-2xl font-bold {{ $netPnl >= 0 ? 'text-green-500' : 'text-red-500' }}"
                    x-data="{ display: 0, target: {{ abs($netPnl) }} }" x-init="{{ $stepFn }}"
                    x-text="'{{ $netPnl >= 0 ? '+' : '-' }}$' + display.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">$0.00</div>
            </div>
        </flux:card>
    </div>

    {{-- Trader Cards --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <flux:heading size="lg" class="text-zinc-900 dark:text-white">Top Traders</flux:heading>
            <flux:input wire:model.live.debounce.400ms="search" placeholder="Search by trader name..." class="max-w-xs" icon="magnifying-glass" />
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mr-1">Risk</span>
            @foreach (['all' => 'All Risk', 'low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                <button type="button" wire:click="$set('riskFilter', '{{ $value }}')"
                    class="px-3 py-1 text-xs font-medium rounded-full border transition-colors {{ $riskFilter === $value ? 'border-teal-500 bg-teal-500/10 text-teal-600 dark:text-teal-400' : 'border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            @forelse ($traders as $trader)
                <flux:card class="trading-card group !p-0 overflow-hidden">
                    <div class="flex flex-col gap-4 px-5 pb-5 pt-5">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <span class="flex size-10 rounded-full overflow-hidden bg-zinc-100 transition-transform duration-300 group-hover:scale-105">
                                        <img src="{{ $trader->avatarUrl() }}" alt="{{ $trader->name }}" loading="lazy"
                                            class="w-full h-full object-cover" onerror="avatarImgFallback(this, '{{ $trader->avatar_initials ?? substr($trader->name, 0, 2) }}')">
                                    </span>
                                    <span class="absolute -bottom-0.5 -right-0.5 size-3 bg-green-500 rounded-full border-2 border-white dark:border-zinc-900"></span>
                                </div>
                                <div class="min-w-0">
                                    <flux:heading size="sm" class="truncate">{{ $trader->name }}</flux:heading>
                                    <flux:text size="sm" class="text-zinc-500 truncate block">{{ $trader->tagline }}</flux:text>
                                </div>
                            </div>
                            @php $risk = $trader->riskBadgeClasses(); @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 {{ $risk['pill'] }}">
                                <span class="size-1.5 rounded-full {{ $risk['dot'] }}"></span>
                                {{ ucfirst($trader->risk_level) }} Risk
                            </span>
                        </div>

                        @if ($trader->bio)
                            <flux:text size="sm" class="text-zinc-500 line-clamp-2">{{ $trader->bio }}</flux:text>
                        @endif

                        <div class="grid grid-cols-3 gap-2 p-3 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl">
                            <div>
                                <flux:text size="xs" class="text-zinc-500 uppercase tracking-wide font-semibold">ROI (30d)</flux:text>
                                <flux:heading size="sm" class="font-mono text-green-500 mt-0.5">+{{ $trader->roi_30d }}%</flux:heading>
                            </div>
                            <div>
                                <flux:text size="xs" class="text-zinc-500 uppercase tracking-wide font-semibold">Win Rate</flux:text>
                                <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white mt-0.5">{{ $trader->win_rate }}%</flux:heading>
                            </div>
                            <div>
                                <flux:text size="xs" class="text-zinc-500 uppercase tracking-wide font-semibold">Copiers</flux:text>
                                <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white mt-0.5">{{ number_format($trader->totalCopiers()) }}</flux:heading>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <flux:text size="xs" class="text-zinc-500 uppercase tracking-wide font-semibold">30-Day Growth</flux:text>
                                <flux:text size="xs" class="font-mono font-semibold text-green-500">+{{ $trader->roi_30d }}%</flux:text>
                            </div>
                            @php $spark = $sparkPaths[$trader->id % count($sparkPaths)]; @endphp
                            <svg viewBox="0 0 100 32" class="w-full h-12 text-teal-500" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="spark-fill-{{ $trader->id }}" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="currentColor" stop-opacity="0.25" />
                                        <stop offset="100%" stop-color="currentColor" stop-opacity="0" />
                                    </linearGradient>
                                </defs>
                                <polygon points="{{ $spark }} 100,32 0,32" fill="url(#spark-fill-{{ $trader->id }})" />
                                <polyline points="{{ $spark }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <div class="flex items-center gap-1.5 text-xs text-zinc-500">
                            <flux:icon name="scale" variant="outline" class="size-3.5" />
                            Min ${{ number_format($trader->min_copy_amount, 0) }}{{ $trader->max_copy_amount ? ' — Max $' . number_format($trader->max_copy_amount, 0) : '' }}
                        </div>

                        <flux:button variant="primary" class="w-full" icon="document-duplicate" wire:click="openCopyModal({{ $trader->id }})">
                            Copy Trader
                        </flux:button>
                    </div>
                </flux:card>
            @empty
                <flux:card class="md:col-span-3 flex flex-col items-center justify-center gap-2 py-12 text-center">
                    <flux:icon name="users" class="size-6 text-zinc-300 dark:text-zinc-700" />
                    <flux:text class="text-zinc-500">No traders match your search or filter.</flux:text>
                </flux:card>
            @endforelse
        </div>
    </div>

    <flux:modal name="copy-modal" class="max-w-md md:min-w-md" wire:model="showCopyModal">
        @if ($selectedTrader)
            <div class="flex flex-col gap-5">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 rounded-full overflow-hidden bg-zinc-100 shrink-0">
                        <img src="{{ $selectedTrader->avatarUrl() }}" alt="{{ $selectedTrader->name }}" loading="lazy"
                            class="w-full h-full object-cover" onerror="avatarImgFallback(this, '{{ $selectedTrader->avatar_initials ?? substr($selectedTrader->name, 0, 2) }}')">
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <flux:heading size="lg">{{ $selectedTrader->name }}</flux:heading>
                            @php $selectedRisk = $selectedTrader->riskBadgeClasses(); @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $selectedRisk['pill'] }}">
                                <span class="size-1.5 rounded-full {{ $selectedRisk['dot'] }}"></span>
                                {{ ucfirst($selectedTrader->risk_level) }} Risk
                            </span>
                        </div>
                        <flux:text size="sm" class="text-zinc-500">{{ $selectedTrader->tagline }}</flux:text>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 p-3 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl">
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Win Rate</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">{{ $selectedTrader->win_rate }}%</flux:heading>
                    </div>
                    <div>
                        <flux:text size="xs" class="text-zinc-500">ROI (30d)</flux:text>
                        <flux:heading size="sm" class="font-mono text-green-500">+{{ $selectedTrader->roi_30d }}%</flux:heading>
                    </div>
                    <div>
                        <flux:text size="xs" class="text-zinc-500">Your Balance</flux:text>
                        <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">${{ number_format($balance, 0) }}</flux:heading>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <flux:text size="sm" class="text-zinc-500">Amount to Allocate (USD)</flux:text>
                        <flux:text size="sm" class="text-zinc-400">
                            Min ${{ number_format($selectedTrader->min_copy_amount, 0) }}{{ $selectedTrader->max_copy_amount ? ' · Max $' . number_format($selectedTrader->max_copy_amount, 0) : '' }}
                        </flux:text>
                    </div>
                    <flux:input wire:model="amount" type="number" step="0.01" icon="currency-dollar" placeholder="0.00" />
                </div>

                <flux:callout variant="warning" icon="exclamation-triangle">
                    Your allocation follows {{ $selectedTrader->name }}'s trades automatically. You can stop copying at any time, but past results don't guarantee future returns.
                </flux:callout>

                <div class="flex gap-3">
                    <flux:button variant="outline" wire:click="closeCopyModal" class="flex-1">Cancel</flux:button>
                    <flux:button variant="primary" wire:click="copy" class="flex-1">Confirm Copy</flux:button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
