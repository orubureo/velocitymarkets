<div class="flex flex-col gap-8 stagger-children">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">My Subscriptions</flux:heading>
            <flux:text class="text-zinc-500">Manage your expert trader subscriptions.</flux:text>
        </div>
        <flux:button variant="primary" icon="sparkles" :href="route('copy-trading')" wire:navigate>
            Browse Traders
        </flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    @if ($mySubscriptions->isNotEmpty())
        <flux:card class="trading-card !p-0 overflow-hidden">
            <flux:table>
                <flux:table.columns class="[&_th]:!py-4">
                    <flux:table.column>Trader</flux:table.column>
                    <flux:table.column>Allocated</flux:table.column>
                    <flux:table.column>Net P&amp;L</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($mySubscriptions as $sub)
                        <flux:table.row wire:key="sub-{{ $sub->id }}" class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <flux:table.cell class="!py-4 font-medium text-zinc-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-8 rounded-full overflow-hidden bg-zinc-100 shrink-0 transition-transform duration-300 group-hover:scale-110">
                                        <img src="{{ $sub->trader->avatarUrl() }}" alt="{{ $sub->trader->name }}" loading="lazy"
                                            class="w-full h-full object-cover" onerror="avatarImgFallback(this, '{{ $sub->trader->avatar_initials ?? substr($sub->trader->name, 0, 2) }}')">
                                    </span>
                                    <span class="whitespace-nowrap">{{ $sub->trader->name }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="!py-4 font-mono">${{ number_format($sub->amount, 2) }}</flux:table.cell>
                            <flux:table.cell class="!py-4 font-mono font-medium {{ $sub->netPnl() >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                {{ $sub->netPnl() >= 0 ? '+' : '-' }}${{ number_format(abs($sub->netPnl()), 2) }}
                            </flux:table.cell>
                            <flux:table.cell class="!py-4">
                                @if ($sub->isActive())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                        <span class="relative flex size-1.5">
                                            <span class="absolute inline-flex h-full w-full rounded-full bg-green-500 pulse-dot-ping"></span>
                                            <span class="relative inline-flex size-1.5 rounded-full bg-green-500"></span>
                                        </span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-zinc-500/10 text-zinc-600 dark:text-zinc-400 border border-zinc-500/20">
                                        Stopped
                                    </span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="!py-4">
                                @if ($sub->isActive())
                                    <button type="button" wire:click="stopCopy({{ $sub->id }})" wire:confirm="Stop copying this trader?"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-red-600 transition-colors">
                                        <flux:icon name="stop-circle" variant="outline" class="size-4" />
                                        Stop Copying
                                    </button>
                                @else
                                    <span class="text-xs text-zinc-400">&mdash;</span>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card>
    @else
        <flux:card class="trading-card flex flex-col items-center justify-center gap-3 py-20 text-center">
            <div class="size-16 rounded-full bg-teal-500/10 flex items-center justify-center">
                <flux:icon name="square-2-stack" class="size-8 text-teal-500" />
            </div>
            <flux:heading size="lg" class="text-zinc-900 dark:text-white">No Active Subscriptions</flux:heading>
            <flux:text class="text-zinc-500 max-w-sm">
                Start copying expert traders to grow your wealth automatically.
            </flux:text>
            <flux:button variant="primary" icon="sparkles" :href="route('copy-trading')" wire:navigate class="mt-2">
                Browse Expert Traders
            </flux:button>
        </flux:card>
    @endif
</div>
