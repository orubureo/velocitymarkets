<div class="flex flex-col gap-6 stagger-children">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl">Wallet</flux:heading>
            <flux:text class="text-zinc-500">Manage your deposits and withdrawals in one place.</flux:text>
        </div>

        <flux:radio.group wire:model.live="tab" variant="segmented">
            <flux:radio value="deposit" icon="arrow-down-tray">Deposit</flux:radio>
            <flux:radio value="withdraw" icon="arrow-up-tray">Withdraw</flux:radio>
        </flux:radio.group>
    </div>

    {{-- Balance banner --}}
    <div class="relative rounded-3xl bg-gradient-to-br from-zinc-900 via-zinc-900 to-black p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 overflow-hidden text-white shadow-xl shadow-zinc-900/10">
        <div class="absolute -top-16 -right-16 size-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none animate-blob-drift"></div>
        <div class="absolute -bottom-20 -left-10 size-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none animate-blob-drift-slow"></div>
        <div class="absolute inset-0 opacity-[0.04] [background-image:radial-gradient(circle_at_1px_1px,white_1px,transparent_0)] [background-size:18px_18px] pointer-events-none"></div>

        <div class="flex items-center gap-3 relative z-10">
            <div class="p-2.5 rounded-xl bg-white/10 shrink-0">
                <flux:icon name="wallet" variant="outline" class="size-6 text-teal-400" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-400">Available balance</flux:text>
                <flux:heading size="xl" class="font-mono text-white">${{ number_format($balance, 2) }}</flux:heading>
            </div>
        </div>

        <div class="inline-flex items-center gap-1.5 text-sm text-teal-400 relative z-10 shrink-0">
            <flux:icon name="check-circle" variant="outline" class="size-4" />
            Ready to trade or withdraw
        </div>
    </div>

    {{-- Deposit / Withdraw wizard --}}
    <div class="w-full">
        @if ($tab === 'deposit')
            <livewire:wallet.deposit :key="'wallet-deposit-tab'" />
        @else
            <livewire:wallet.withdraw :key="'wallet-withdraw-tab'" />
        @endif
    </div>

    {{-- Trust strip --}}
    <div class="flex flex-col gap-3">
        <flux:heading size="sm" class="text-zinc-500">Why VelocityMarkets</flux:heading>
        <flux:card class="trading-card grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="group flex items-start gap-3">
                <div class="stat-icon-up !rounded-lg shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="shield-check" class="size-4" />
                </div>
                <div>
                    <div class="text-sm font-medium text-zinc-900 dark:text-white">Secure by design</div>
                    <div class="text-xs text-zinc-500 mt-0.5">Your funds and data are protected with bank-level encryption.</div>
                </div>
            </div>

            <div class="group flex items-start gap-3">
                <div class="stat-icon-sky !rounded-lg shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="bolt" class="size-4" />
                </div>
                <div>
                    <div class="text-sm font-medium text-zinc-900 dark:text-white">Fast processing</div>
                    <div class="text-xs text-zinc-500 mt-0.5">Most requests are reviewed and confirmed within minutes.</div>
                </div>
            </div>

            <div class="group flex items-start gap-3">
                <div class="stat-icon-purple !rounded-lg shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <flux:icon name="lifebuoy" class="size-4" />
                </div>
                <div>
                    <div class="text-sm font-medium text-zinc-900 dark:text-white">24/7 support</div>
                    <div class="text-xs text-zinc-500 mt-0.5">Our team is on hand if anything doesn't look right.</div>
                </div>
            </div>
        </flux:card>
    </div>
</div>
