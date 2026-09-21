<div class="flex flex-col gap-6 stagger-children">
    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    @if (session('error'))
        <flux:callout variant="danger" icon="x-circle">
            {{ session('error') }}
        </flux:callout>
    @endif

    @if ($withdrawalsPaused)
        <flux:callout variant="danger" icon="lock-closed">
            Withdrawals are currently paused for your account. Please contact support for assistance.
        </flux:callout>
    @else
    {{-- Step indicator --}}
    <x-wizard-steps :labels="['Method', 'Amount', 'Review']" :current="$step" />

    {{-- Step 1: choose method, then currency only for crypto --}}
    @if ($step === 1 && ! $awaitingCurrency)
        <flux:card class="trading-card flex flex-col gap-5">
            <div class="flex items-center gap-3">
                <div class="stat-icon-brand !rounded-xl">
                    <flux:icon name="arrow-up-tray" class="size-5" />
                </div>
                <div>
                    <flux:heading size="md">Choose withdrawal method</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">How would you like to receive your funds?</flux:text>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                    type="button"
                    wire:click="selectMethod('bank_transfer')"
                    class="group flex items-center gap-3 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-blue-500 hover:bg-blue-500/5 hover:-translate-y-0.5 hover:shadow-md transition-all text-left cursor-pointer"
                >
                    <div class="p-2.5 bg-blue-500/10 rounded-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="building-library" class="size-5 text-blue-500" />
                    </div>
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white">Bank Transfer</div>
                        <div class="text-xs text-zinc-500">To your saved bank account</div>
                    </div>
                    <flux:icon name="chevron-right" class="size-4 text-zinc-300 dark:text-zinc-600 ml-auto shrink-0 transition-transform duration-200 group-hover:translate-x-0.5" />
                </button>

                <button
                    type="button"
                    wire:click="selectMethod('crypto')"
                    class="group flex items-center gap-3 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-teal-500 hover:bg-teal-500/5 hover:-translate-y-0.5 hover:shadow-md transition-all text-left cursor-pointer"
                >
                    <div class="p-2.5 bg-teal-500/10 rounded-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <flux:icon name="currency-dollar" class="size-5 text-teal-500" />
                    </div>
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white">Crypto</div>
                        <div class="text-xs text-zinc-500">To a crypto wallet address</div>
                    </div>
                    <flux:icon name="chevron-right" class="size-4 text-zinc-300 dark:text-zinc-600 ml-auto shrink-0 transition-transform duration-200 group-hover:translate-x-0.5" />
                </button>
            </div>
        </flux:card>
    @endif

    {{-- Step 1b: crypto currency sub-choice --}}
    @if ($step === 1 && $awaitingCurrency)
        <flux:card class="trading-card flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <flux:badge size="sm" color="teal">Crypto</flux:badge>
                <flux:text class="text-zinc-500">Choose the currency you'll withdraw as.</flux:text>
            </div>

            @php
                // Hover accents match each asset's official brand color now that real logos are shown.
                $currencyHover = fn (string $c) => match ($c) {
                    'BTC' => 'hover:border-amber-500 hover:bg-amber-500/5',
                    'ETH' => 'hover:border-indigo-500 hover:bg-indigo-500/5',
                    'USDT' => 'hover:border-teal-500 hover:bg-teal-500/5',
                    default => 'hover:border-teal-500 hover:bg-teal-500/5',
                };
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach ($cryptoCurrencies as $currencyOption)
                    <button
                        type="button"
                        wire:click="selectCurrency('{{ $currencyOption }}')"
                        class="group flex flex-col items-center gap-2 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:-translate-y-0.5 hover:shadow-md transition-all cursor-pointer {{ $currencyHover($currencyOption) }}"
                    >
                        <x-crypto-icon :currency="$currencyOption" class="size-10 transition-transform duration-300 group-hover:scale-110" />
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $currencyOption }}</span>
                    </button>
                @endforeach
            </div>

            <flux:button variant="outline" wire:click="backToMethod">Back</flux:button>
        </flux:card>
    @endif

    {{-- Step 2: amount + destination --}}
    @if ($step === 2)
        <flux:card class="trading-card flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand !rounded-xl">
                        <flux:icon name="banknotes" class="size-5" />
                    </div>
                    <flux:heading size="md">Amount &amp; destination</flux:heading>
                </div>
                <flux:badge size="sm" color="{{ $this->methodColor() }}">{{ $this->methodLabel() }}</flux:badge>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <flux:text size="sm" class="text-zinc-500">Amount (USD)</flux:text>
                    <button type="button" wire:click="$set('amount', '{{ (string) $balance }}')"
                        class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline">
                        Use max &middot; ${{ number_format($balance, 2) }}
                    </button>
                </div>
                <flux:input wire:model="amount" type="number" step="0.01" placeholder="0.00" />
            </div>

            <div>
                <flux:input wire:model="destination"
                    label="{{ $method === 'bank_transfer' ? 'Bank account' : 'Wallet address' }}"
                    placeholder="{{ $method === 'bank_transfer' ? 'Bank name, account name & number' : 'Destination wallet address' }}" />
                <flux:text size="sm" class="text-zinc-500 mt-1.5 flex items-start gap-1.5">
                    <flux:icon name="information-circle" class="size-4 shrink-0 mt-0.5" />
                    <span>Pulled from your saved <flux:link href="{{ route('profile.edit') }}" wire:navigate>payment settings</flux:link> &mdash; edit here for a one-off destination.</span>
                </flux:text>
            </div>

            <div class="flex gap-2 pt-1">
                <flux:button variant="outline" wire:click="backToMethod">Back</flux:button>
                <flux:button variant="primary" wire:click="proceedToReview" class="flex-1">
                    Review Withdrawal
                </flux:button>
            </div>
        </flux:card>
    @endif

    {{-- Step 3: review & confirm --}}
    @if ($step === 3)
        <flux:card class="trading-card flex flex-col gap-5 !p-0 overflow-hidden">
            <div class="relative bg-gradient-to-br from-teal-600 to-emerald-700 dark:from-teal-900 dark:to-zinc-950 px-6 py-6 overflow-hidden">
                <div class="absolute -top-8 -right-8 size-32 bg-white/10 rounded-full blur-2xl pointer-events-none animate-blob-drift"></div>
                <div class="flex items-center gap-2 relative z-10">
                    @if ($method === 'crypto')
                        <x-crypto-icon :currency="$currency" class="size-8 ring-2 ring-white/20" />
                    @endif
                    <flux:badge size="sm" color="{{ $this->methodColor() }}">{{ $this->methodLabel() }}</flux:badge>
                </div>
                <div class="mt-3 relative z-10">
                    <flux:text size="sm" class="text-white/70">You're withdrawing</flux:text>
                    <div class="text-3xl font-bold font-mono text-white mt-0.5">${{ number_format((float) $amount, 2) }}</div>
                </div>
            </div>

            <div class="flex flex-col gap-4 px-6 pb-6">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-800 overflow-hidden">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <flux:icon name="{{ $method === 'bank_transfer' ? 'building-library' : 'currency-dollar' }}" class="size-4 text-zinc-400 shrink-0" />
                        <flux:text size="sm" class="text-zinc-500 shrink-0">Method</flux:text>
                        <flux:text size="sm" class="font-medium text-zinc-900 dark:text-white ml-auto">{{ $this->methodLabel() }}</flux:text>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-3">
                        <flux:icon name="map-pin" class="size-4 text-zinc-400 shrink-0" />
                        <flux:text size="sm" class="text-zinc-500 shrink-0">Destination</flux:text>
                        <flux:text size="sm" class="font-mono text-right text-zinc-900 dark:text-white truncate ml-auto">{{ $destination }}</flux:text>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-3">
                        <flux:icon name="banknotes" class="size-4 text-zinc-400 shrink-0" />
                        <flux:text size="sm" class="text-zinc-500 shrink-0">Amount</flux:text>
                        <flux:text size="sm" class="font-mono font-semibold text-zinc-900 dark:text-white ml-auto">${{ number_format((float) $amount, 2) }}</flux:text>
                    </div>
                </div>

                <flux:callout variant="warning" icon="exclamation-triangle">
                    Withdrawals are reviewed and processed by an admin. Double-check your destination — this cannot be reversed once sent.
                </flux:callout>

                <div class="flex gap-2">
                    <flux:button variant="outline" wire:click="backToAmount">Back</flux:button>
                    <flux:button variant="primary" wire:click="submit" class="flex-1">
                        Submit Withdrawal Request
                    </flux:button>
                </div>
            </div>
        </flux:card>
    @endif
    @endif
</div>
