<div class="flex flex-col gap-8">
    <flux:link variant="subtle" href="{{ route('admin.users') }}" wire:navigate class="inline-flex items-center gap-1.5 w-fit">
        <flux:icon name="arrow-left" class="size-4" />
        Users
    </flux:link>

    {{-- Identity --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0">
            <flux:avatar :name="$user->name" color="auto" size="xl" />
            <div class="min-w-0">
                <flux:heading size="xl" class="text-zinc-900 dark:text-white truncate">{{ $user->name }}</flux:heading>
                <flux:text class="text-zinc-500 truncate block" title="{{ $user->email }}">{{ $user->email }}</flux:text>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <x-status-badge :status="$user->kyc_status" />
            <flux:text size="sm" class="text-zinc-400">Joined {{ $user->created_at->format('M j, Y') }}</flux:text>
        </div>
    </div>

    <flux:separator />

    {{-- Wallet --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <flux:card class="trading-card lg:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <flux:text size="sm" class="text-zinc-500 font-medium">Wallet Balance</flux:text>
                <div class="text-4xl font-bold font-mono tabular-nums text-zinc-900 dark:text-white mt-1">
                    ${{ number_format($wallet->balance ?? 0, 2) }}
                </div>
            </div>

            @if ($wallet)
                <div class="flex gap-2 shrink-0">
                    <flux:button variant="primary" icon="plus" wire:click="openAdjustModal('credit')">Credit</flux:button>
                    <flux:button variant="danger" icon="minus" wire:click="openAdjustModal('debit')">Debit</flux:button>
                </div>
            @else
                <flux:text size="sm" class="text-zinc-500">No wallet on file</flux:text>
            @endif
        </flux:card>

        <flux:card class="trading-card flex flex-col justify-center gap-4">
            <div class="flex items-center justify-between gap-4">
                <flux:text size="sm" class="text-zinc-500">Deposited</flux:text>
                <flux:text class="font-mono font-semibold text-green-600 dark:text-green-400">${{ number_format($totalDeposits, 2) }}</flux:text>
            </div>
            <div class="flex items-center justify-between gap-4">
                <flux:text size="sm" class="text-zinc-500">Withdrawn</flux:text>
                <flux:text class="font-mono font-semibold text-red-600 dark:text-red-400">${{ number_format($totalWithdrawals, 2) }}</flux:text>
            </div>
            <div class="flex items-center justify-between gap-4">
                <flux:text size="sm" class="text-zinc-500">Trades placed</flux:text>
                <flux:text class="font-mono font-semibold text-zinc-900 dark:text-white">{{ number_format($tradesCount) }}</flux:text>
            </div>
        </flux:card>
    </div>

    {{-- Details --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-8">
        <div>
            <flux:heading class="uppercase tracking-wide text-xs text-zinc-400 mb-4">Profile</flux:heading>
            <dl class="grid grid-cols-2 gap-y-4">
                <dt class="text-sm text-zinc-500">Full name</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $user->name }}</dd>

                <dt class="text-sm text-zinc-500">Phone</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $user->phone ?? '—' }}</dd>

                <dt class="text-sm text-zinc-500">Country</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $user->country ?? '—' }}</dd>
            </dl>
        </div>

        <div>
            <flux:heading class="uppercase tracking-wide text-xs text-zinc-400 mb-4">Account</flux:heading>
            <dl class="grid grid-cols-2 gap-y-4">
                <dt class="text-sm text-zinc-500">Referral code</dt>
                <dd class="text-sm font-mono font-medium text-zinc-900 dark:text-white text-right">{{ $user->referral_code ?? '—' }}</dd>

                <dt class="text-sm text-zinc-500">Referred by</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right truncate min-w-0">{{ $user->referrer?->name ?? '—' }}</dd>

                <dt class="text-sm text-zinc-500">Open tickets</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $openTickets }}</dd>
            </dl>
        </div>
    </div>

    @if ($wallet)
        <flux:modal name="adjust-balance-modal" class="max-w-md md:min-w-md" wire:model="showAdjustModal">
            <div class="space-y-6">
                <flux:heading size="lg">{{ $adjustType === 'credit' ? 'Credit' : 'Debit' }} Wallet</flux:heading>

                <flux:input wire:model="adjustAmount" label="Amount (USD)" type="number" step="0.01" placeholder="0.00" />
                <flux:input wire:model="adjustNote" label="Note" placeholder="Reason for this adjustment" />

                <div class="flex gap-3 justify-end">
                    <flux:button variant="outline" wire:click="closeAdjustModal">Cancel</flux:button>
                    <flux:button variant="{{ $adjustType === 'credit' ? 'primary' : 'danger' }}" wire:click="adjustBalance">
                        {{ $adjustType === 'credit' ? 'Credit' : 'Debit' }} Wallet
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
