<div class="flex flex-col gap-8 stagger-children">
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Investment Plans</flux:heading>
        <flux:text class="text-zinc-500">Choose a plan and start growing your portfolio.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    @php
        $bestRoi = $plans->max('roi_percent');
        $faqs = [
            [
                'q' => 'How are returns calculated?',
                'a' => "Each plan lists a total ROI over its full duration — the daily estimate shown on the card is simply that total split evenly across the plan's days. Returns accrue over the term and are credited to your wallet balance.",
            ],
            [
                'q' => 'How secure are my investments?',
                'a' => 'Invested funds are held in your VelocityMarkets wallet and protected the same way as the rest of your account. As with any investment, returns are not guaranteed — only invest what you\'re comfortable committing for the full term.',
            ],
            [
                'q' => 'Can I withdraw before the term ends?',
                'a' => "No — once you invest in a plan, your principal is locked for the full term. It's released back to your available balance, along with any accrued returns, once the plan matures.",
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-2 flex flex-col gap-8">
            {{-- Available Plans --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse ($plans as $plan)
                    <flux:card class="trading-card flex flex-col gap-4 relative !p-0">
                        <div class="flex flex-col gap-4 px-6 pb-6 pt-6 relative">
                            @if ($plan->roi_percent == $bestRoi)
                                <flux:badge size="sm" color="lime" class="absolute top-2 right-6 z-10">Best ROI</flux:badge>
                            @endif

                            <div class="flex items-start justify-between">
                                <div>
                                    <flux:heading size="lg">{{ $plan->name }}</flux:heading>
                                    @if ($plan->description)
                                        <flux:text size="sm" class="text-zinc-500">{{ $plan->description }}</flux:text>
                                    @endif
                                </div>
                                <div class="stat-icon-brand">
                                    <flux:icon name="briefcase" class="size-5" />
                                </div>
                            </div>

                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <flux:text size="sm" class="text-zinc-500">Min &mdash; Max</flux:text>
                                <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">
                                    ${{ number_format($plan->min_amount, 0) }} &ndash; ${{ number_format($plan->max_amount, 0) }}
                                </flux:heading>
                            </div>
                            <div>
                                <flux:text size="sm" class="text-zinc-500">Total ROI</flux:text>
                                <flux:heading size="sm" class="font-mono text-green-500">{{ $plan->roi_percent }}%</flux:heading>
                            </div>
                            <div>
                                <flux:text size="sm" class="text-zinc-500">Duration</flux:text>
                                <flux:heading size="sm" class="font-mono text-zinc-900 dark:text-white">{{ $plan->duration_days }} Days</flux:heading>
                            </div>
                            <div>
                                <flux:text size="sm" class="text-zinc-500">Daily Est.</flux:text>
                                <flux:heading size="sm" class="font-mono text-green-500">{{ $plan->dailyRoiPercent() }}%</flux:heading>
                            </div>
                        </div>

                            <flux:button variant="primary" class="w-full" wire:click="openInvestModal({{ $plan->id }})">
                                Invest Now
                            </flux:button>
                        </div>
                    </flux:card>
                @empty
                    <flux:card class="sm:col-span-2 text-center py-12 text-zinc-500">
                        No investment plans are available right now.
                    </flux:card>
                @endforelse
            </div>

            {{-- My Investments --}}
            <div class="flex flex-col gap-4">
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">My Investments</flux:heading>

                <flux:card class="trading-card !p-0 overflow-hidden">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Plan</flux:table.column>
                            <flux:table.column>Amount</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
                            <flux:table.column>Progress</flux:table.column>
                            <flux:table.column>ROI Paid</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse ($myInvestments as $investment)
                                <flux:table.row wire:key="investment-{{ $investment->id }}">
                                    <flux:table.cell class="font-medium text-zinc-900 dark:text-white">{{ $investment->plan->name }}</flux:table.cell>
                                    <flux:table.cell class="font-mono">${{ number_format($investment->amount, 2) }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge size="sm" color="{{ match ($investment->status) {
                                            'active' => 'lime',
                                            'completed' => 'violet',
                                            default => 'zinc',
                                        } }}">
                                            {{ ucfirst($investment->status) }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <div class="flex items-center gap-2 w-32">
                                            <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-1.5">
                                                <div class="bg-violet-500 h-1.5 rounded-full" style="width: {{ $investment->progressPercent() }}%"></div>
                                            </div>
                                            <span class="text-xs text-zinc-500 shrink-0">{{ $investment->daysRemaining() }}d left</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell class="font-mono text-green-500">${{ number_format($investment->totalRoiPaid(), 2) }}</flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="5" class="text-center text-zinc-500 py-8">
                                        You haven't invested in any plan yet.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-6">
            <flux:card class="trading-card">
                <div class="flex items-center gap-3 mb-5">
                    <div class="stat-icon-brand">
                        <flux:icon name="flag" class="size-5" />
                    </div>
                    <flux:heading size="md">Your Investment Journey</flux:heading>
                </div>
                <div class="flex flex-col gap-5">
                    @foreach ([
                        ['title' => 'Choose Your Plan', 'text' => 'Compare returns, duration and minimum stake across our plans, and pick the one that matches your goals and risk tolerance.'],
                        ['title' => 'Fund Your Investment', 'text' => 'Confirm your amount and it\'s deducted from your wallet balance — start with as little as $100.'],
                        ['title' => 'Track & Grow', 'text' => 'Watch your plan\'s progress and see returns credited to your wallet balance as your investment matures.'],
                    ] as $i => $step)
                        <div class="flex gap-3">
                            <div class="stat-icon-brand !rounded-full !p-0 size-7 shrink-0 flex items-center justify-center text-sm font-bold">
                                {{ $i + 1 }}
                            </div>
                            <div>
                                <flux:heading size="sm" class="text-zinc-900 dark:text-white">{{ $step['title'] }}</flux:heading>
                                <flux:text size="sm" class="text-zinc-500">{{ $step['text'] }}</flux:text>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>

            <flux:card class="trading-card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="stat-icon-brand">
                        <flux:icon name="question-mark-circle" class="size-5" />
                    </div>
                    <flux:heading size="md">Common Questions</flux:heading>
                </div>
                <div class="flex flex-col gap-2">
                    @foreach ($faqs as $faq)
                        <div x-data="{ open: false }" class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                            <button type="button" @click="open = !open"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <flux:text size="sm" class="font-semibold text-zinc-900 dark:text-white">{{ $faq['q'] }}</flux:text>
                                <flux:icon name="chevron-down" class="size-4 text-zinc-400 shrink-0 transition-transform duration-200" x-bind:class="open && 'rotate-180'" />
                            </button>
                            <div x-show="open" x-collapse x-cloak>
                                <flux:text size="sm" class="block text-zinc-500 px-4 pb-3">{{ $faq['a'] }}</flux:text>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        </div>
    </div>

    {{-- Investment Insights --}}
    @if ($plans->isNotEmpty())
        <div class="flex flex-col gap-3">
            <flux:heading size="sm" class="text-zinc-500">Investment Insights</flux:heading>
            <flux:card class="trading-card grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="group flex items-start gap-3">
                    <div class="stat-icon-up !rounded-lg shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="arrow-trending-up" class="size-4" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">Performance</div>
                        <div class="text-xs text-zinc-500 mt-0.5">Every plan has a fixed return set upfront, averaging {{ number_format($plans->avg('roi_percent'), 1) }}% ROI across our plans.</div>
                    </div>
                </div>

                <div class="group flex items-start gap-3">
                    <div class="stat-icon-sky !rounded-lg shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="shield-check" class="size-4" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">Risk Management</div>
                        <div class="text-xs text-zinc-500 mt-0.5">Returns are fixed for the full term, so your plan isn't affected by market swings once it starts.</div>
                    </div>
                </div>

                <div class="group flex items-start gap-3">
                    <div class="stat-icon-violet !rounded-lg shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="clock" class="size-4" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">Liquidity</div>
                        <div class="text-xs text-zinc-500 mt-0.5">Your principal is committed for {{ $plans->min('duration_days') }}–{{ $plans->max('duration_days') }} days and is paid out, with your returns, at maturity.</div>
                    </div>
                </div>
            </flux:card>
        </div>
    @endif

    <flux:modal name="invest-modal" class="max-w-3xl md:min-w-3xl" wire:model="showInvestModal">
        @if ($selectedPlan)
            <div class="flex flex-col gap-6" x-data="{
                    amount: @entangle('amount'),
                    min: {{ (float) $selectedPlan->min_amount }},
                    max: {{ (float) $selectedPlan->max_amount }},
                    roi: {{ (float) $selectedPlan->roi_percent }},
                    get numAmount() { return parseFloat(this.amount) || 0 },
                    get estReturn() { return this.numAmount * this.roi / 100 },
                    get total() { return this.numAmount + this.estReturn },
                    fmt(n) { return n.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) },
                }">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <flux:heading size="lg">{{ $selectedPlan->name }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">{{ $selectedPlan->duration_days }} Days plan with {{ $selectedPlan->dailyRoiPercent() }}% Daily returns</flux:text>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20">Min: ${{ number_format($selectedPlan->min_amount, 0) }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20">Max: ${{ number_format($selectedPlan->max_amount, 0) }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20">{{ $selectedPlan->roi_percent }}% ROI</span>
                    </div>
                </div>

                {{-- Duration / Return Rate / Bonus --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl">
                    <div class="flex items-center gap-2.5">
                        <div class="stat-icon-brand !rounded-lg">
                            <flux:icon name="calendar-days" class="size-4" />
                        </div>
                        <div>
                            <flux:text size="xs" class="text-zinc-500 block">Duration</flux:text>
                            <flux:heading size="sm" class="text-zinc-900 dark:text-white">{{ $selectedPlan->duration_days }} Days</flux:heading>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="stat-icon-up !rounded-lg">
                            <flux:icon name="chart-bar" class="size-4" />
                        </div>
                        <div>
                            <flux:text size="xs" class="text-zinc-500 block">Return Rate</flux:text>
                            <flux:heading size="sm" class="text-zinc-900 dark:text-white">{{ $selectedPlan->dailyRoiPercent() }}% Daily</flux:heading>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="stat-icon-violet !rounded-lg">
                            <flux:icon name="gift" class="size-4" />
                        </div>
                        <div>
                            <flux:text size="xs" class="text-zinc-500 block">Bonus</flux:text>
                            <flux:heading size="sm" class="text-zinc-900 dark:text-white">$0</flux:heading>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left: amount --}}
                    <div class="flex flex-col gap-4">
                        <div>
                            <flux:text size="sm" class="text-zinc-500 mb-2 block">Quick Amount Selection</flux:text>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ([100, 250, 500, 1000, 2000, 5000] as $amt)
                                    <button type="button" @click="amount = {{ $amt }}; $wire.set('amount', {{ $amt }})"
                                        :class="numAmount === {{ $amt }} ? 'border-teal-500 bg-teal-500/10 text-teal-600 dark:text-teal-400' : 'border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800'"
                                        class="py-2 rounded-lg border text-sm font-semibold transition-colors">
                                        ${{ $amt >= 1000 ? number_format($amt / 1000) . 'K' : $amt }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <flux:text size="sm" class="text-zinc-500 mb-2 block">Or Enter Custom Amount</flux:text>
                            <flux:input wire:model="amount" type="number" step="0.01" icon="currency-dollar" placeholder="0.00" />
                            <input type="range" x-model.number="amount" @input="$wire.set('amount', amount)"
                                min="{{ (float) $selectedPlan->min_amount }}" max="{{ (float) $selectedPlan->max_amount }}" step="1"
                                class="w-full mt-3 accent-teal-500">
                            <div class="flex justify-between text-xs text-zinc-400 -mt-1">
                                <span>${{ number_format($selectedPlan->min_amount, 0) }}</span>
                                <span>${{ number_format($selectedPlan->max_amount, 0) }}</span>
                            </div>
                        </div>

                        <div class="p-4 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl flex flex-col gap-2">
                            <flux:text size="sm" class="font-semibold text-zinc-900 dark:text-white mb-1">Estimated Returns</flux:text>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Initial Investment</span>
                                <span class="font-mono" x-text="'$' + fmt(numAmount)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Est. Return ({{ $selectedPlan->roi_percent }}%)</span>
                                <span class="font-mono text-green-500" x-text="'+$' + fmt(estReturn)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Bonus</span>
                                <span class="font-mono text-green-500">+$0.00</span>
                            </div>
                            <div class="flex justify-between border-t border-zinc-200 dark:border-zinc-800 pt-2 mt-1">
                                <span class="font-semibold text-zinc-900 dark:text-white">Total Value</span>
                                <span class="font-mono font-bold text-zinc-900 dark:text-white" x-text="'$' + fmt(total)"></span>
                            </div>
                            <flux:text size="xs" class="text-zinc-400">After {{ $selectedPlan->duration_days }} days</flux:text>
                        </div>
                    </div>

                    {{-- Right: payment + summary --}}
                    <div class="flex flex-col gap-4">
                        <div>
                            <flux:text size="sm" class="text-zinc-500 mb-2 block">Payment Method</flux:text>
                            <div class="flex items-center gap-3 p-4 border-2 border-teal-500 bg-teal-500/5 rounded-xl">
                                <div class="stat-icon-brand">
                                    <flux:icon name="wallet" class="size-5" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <flux:text class="font-semibold text-zinc-900 dark:text-white block">Account Balance</flux:text>
                                    <flux:text size="sm" class="text-zinc-500">${{ number_format($balance, 2) }} available</flux:text>
                                </div>
                                <flux:icon name="check-circle" variant="solid" class="size-5 text-teal-500 shrink-0" />
                            </div>
                        </div>

                        <div class="p-4 bg-zinc-50 dark:bg-zinc-950/60 rounded-xl flex flex-col gap-2">
                            <flux:text size="sm" class="font-semibold text-zinc-900 dark:text-white mb-1">Investment Summary</flux:text>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Selected Plan</span>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $selectedPlan->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Investment Amount</span>
                                <span class="font-mono text-zinc-900 dark:text-white" x-text="'$' + fmt(numAmount)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Payment Method</span>
                                <span class="font-medium text-zinc-900 dark:text-white">Account Balance</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Duration</span>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $selectedPlan->duration_days }} Days</span>
                            </div>
                        </div>

                        <flux:button variant="primary" class="w-full" icon="arrow-trending-up" wire:click="invest">
                            Confirm &amp; Invest Now
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
