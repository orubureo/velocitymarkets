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

    {{-- Step indicator --}}
    <x-wizard-steps :labels="['Currency', 'Amount', 'Payment']" :current="$step" />

    @php
        // Hover accents match each asset's official brand color now that real logos are shown.
        $currencyHover = fn (string $c) => match ($c) {
            'BTC' => 'hover:border-amber-500 hover:bg-amber-500/5',
            'ETH' => 'hover:border-indigo-500 hover:bg-indigo-500/5',
            'USDT' => 'hover:border-teal-500 hover:bg-teal-500/5',
            'SOL' => 'hover:border-purple-500 hover:bg-purple-500/5',
            default => 'hover:border-teal-500 hover:bg-teal-500/5',
        };
    @endphp

    {{-- Step 1: choose currency, then network for any currency with more than one configured --}}
    @if ($step === 1 && ! $awaitingNetwork)
        <flux:card class="trading-card flex flex-col gap-5">
            <div class="flex items-center gap-3">
                <div class="stat-icon-brand !rounded-xl">
                    <flux:icon name="arrow-down-tray" class="size-5" />
                </div>
                <div>
                    <flux:heading size="md">Choose your crypto asset</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Pick the currency you want to deposit with.</flux:text>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @forelse ($availableCurrencies as $currencyOption)
                    <button
                        type="button"
                        wire:click="selectCurrency('{{ $currencyOption }}')"
                        class="group flex items-center gap-3 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:-translate-y-0.5 hover:shadow-md transition-all text-left cursor-pointer {{ $currencyHover($currencyOption) }}"
                    >
                        <x-crypto-icon :currency="$currencyOption" class="size-11 shrink-0 transition-transform duration-300 group-hover:scale-110" />
                        <div class="min-w-0">
                            <div class="font-semibold text-zinc-900 dark:text-white">{{ $currencyOption }}</div>
                            <div class="text-xs text-zinc-500 truncate">
                                {{ $multiNetworkCurrencies->contains($currencyOption) ? 'Choose a network' : 'Single address' }}
                            </div>
                        </div>
                        <flux:icon name="chevron-right" class="size-4 text-zinc-300 dark:text-zinc-600 ml-auto shrink-0 transition-transform duration-200 group-hover:translate-x-0.5" />
                    </button>
                @empty
                    <div class="sm:col-span-2 lg:col-span-4 text-center py-8 text-zinc-500">
                        No deposit methods are configured yet. Please check back later.
                    </div>
                @endforelse
            </div>
        </flux:card>
    @endif

    {{-- Step 1b: network sub-choice, for whichever currency has more than one configured --}}
    @if ($step === 1 && $awaitingNetwork)
        <flux:card class="trading-card flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <flux:badge size="sm" color="{{ $this->currencyColor() }}">{{ $currency }}</flux:badge>
                <flux:text class="text-zinc-500">Choose the network you'll send from.</flux:text>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @forelse ($availableNetworks as $networkOption)
                    <button
                        type="button"
                        wire:click="selectNetwork('{{ $networkOption }}')"
                        class="flex flex-col items-center gap-1 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-teal-500 hover:bg-teal-500/5 hover:-translate-y-0.5 hover:shadow-md transition-all cursor-pointer"
                    >
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $networkOption }}</span>
                    </button>
                @empty
                    <div class="sm:col-span-3 text-center py-8 text-zinc-500">
                        No {{ $currency }} networks are configured yet. Please check back later.
                    </div>
                @endforelse
            </div>

            <flux:button variant="outline" wire:click="backToMethod">Back</flux:button>
        </flux:card>
    @endif

    {{-- Step 2: amount --}}
    @if ($step === 2)
        <flux:card class="trading-card flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand !rounded-xl">
                        <flux:icon name="calculator" class="size-5" />
                    </div>
                    <flux:heading size="md">How much are you depositing?</flux:heading>
                </div>
                <flux:badge size="sm" color="{{ $this->currencyColor() }}">{{ $currency }}{{ $network ? " ($network)" : '' }}</flux:badge>
            </div>

            <flux:input wire:model="amount" label="Amount (USD)" type="number" step="0.01" placeholder="0.00" />

            <div class="flex flex-wrap gap-2">
                @foreach ([50, 100, 250, 500, 1000] as $preset)
                    <button type="button" wire:click="$set('amount', '{{ $preset }}')"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-500/5 transition-colors">
                        ${{ number_format($preset) }}
                    </button>
                @endforeach
            </div>

            <div class="flex gap-2 pt-1">
                <flux:button variant="outline" wire:click="backToMethod">Back</flux:button>
                <flux:button variant="primary" wire:click="proceedToPayment" class="flex-1">
                    Proceed to Payment
                </flux:button>
            </div>
        </flux:card>
    @endif

    {{-- Step 3: payment (QR + address + proof upload) --}}
    @if ($step === 3 && $selectedWallet)
        <flux:card class="trading-card flex flex-col gap-5 !p-0 overflow-hidden">
            <div class="relative bg-gradient-to-br from-teal-600 to-emerald-700 dark:from-teal-900 dark:to-zinc-950 px-6 py-6 overflow-hidden">
                <div class="absolute -top-8 -left-8 size-32 bg-white/10 blur-2xl pointer-events-none animate-blob-drift"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-2">
                        <x-crypto-icon :currency="$selectedWallet->currency" class="size-8 ring-2 ring-white/20" />
                        <flux:badge size="sm" color="{{ $this->currencyColor() }}">{{ $selectedWallet->label() }}</flux:badge>
                    </div>
                    <div class="text-right">
                        <flux:text size="sm" class="text-white/70">Amount</flux:text>
                        <div class="text-2xl font-bold font-mono text-white">${{ number_format((float) $amount, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-5 px-6 pb-6">
                <div class="flex flex-col items-center gap-4 p-5 rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/50">
                    <div
                        class="bg-white p-3 rounded-lg shadow-sm"
                        x-data
                        :style="($flux.appearance === 'dark' || ($flux.appearance === 'system' && $flux.dark)) ? 'filter: invert(1) brightness(1.5)' : ''"
                    >
                        {!! $qrCodeSvg !!}
                    </div>
                    <flux:text size="sm" class="text-zinc-500 text-center">Scan to send exactly this amount worth of {{ $selectedWallet->currency }}</flux:text>
                </div>

                <div x-data="{ copied: false }">
                    <flux:text size="sm" class="text-zinc-500 mb-1.5">Or send manually to this address:</flux:text>
                    <div class="flex gap-2">
                        <flux:input readonly value="{{ $selectedWallet->address }}" class="font-mono text-sm bg-zinc-50 dark:bg-zinc-950" />
                        <flux:button
                            x-on:click="navigator.clipboard.writeText('{{ $selectedWallet->address }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            icon="clipboard" variant="primary"
                        >
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" x-cloak>Copied!</span>
                        </flux:button>
                    </div>
                </div>

                <label
                    for="proofFile"
                    class="flex flex-col items-center gap-2 p-5 rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 hover:border-teal-500 hover:bg-teal-500/5 transition-colors cursor-pointer text-center"
                >
                    <flux:icon name="cloud-arrow-up" class="size-6 text-zinc-400" />
                    <flux:text size="sm" class="text-zinc-500">
                        @if ($proofFile)
                            <span class="text-green-500 font-medium">Attached: {{ $proofFile->getClientOriginalName() }}</span>
                        @else
                            <span class="font-medium text-zinc-700 dark:text-zinc-300">Upload payment proof</span> (optional) &middot; PNG, JPG or PDF
                        @endif
                    </flux:text>
                    <input id="proofFile" type="file" wire:model="proofFile" accept="image/png,image/jpeg,application/pdf" class="hidden" />
                </label>
                <div wire:loading wire:target="proofFile" class="text-xs text-zinc-500 -mt-3">Uploading&hellip;</div>
                @error('proofFile') <flux:text size="sm" class="text-red-500 -mt-3">{{ $message }}</flux:text> @enderror

                <flux:callout variant="warning" icon="exclamation-triangle">
                    Only send {{ $selectedWallet->currency }} {{ $selectedWallet->network ? "via the {$selectedWallet->network} network " : '' }}to this address. Your deposit will be credited after admin confirmation.
                </flux:callout>

                <div class="flex gap-2">
                    <flux:button variant="outline" wire:click="backToAmount">Back</flux:button>
                    <flux:button variant="primary" wire:click="confirmSent" class="flex-1">
                        I've Sent It
                    </flux:button>
                </div>
            </div>
        </flux:card>
    @endif
</div>
