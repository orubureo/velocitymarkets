<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Withdrawals</flux:heading>
            <flux:text class="text-zinc-500">Review and process pending withdrawal requests.</flux:text>
        </div>
        <flux:badge color="sky" size="lg">Pending Review</flux:badge>
    </div>

    @if (session('error'))
        <flux:callout variant="danger" icon="exclamation-triangle">
            {{ session('error') }}
        </flux:callout>
    @endif

    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Amount</flux:table.column>
                <flux:table.column>Note / Method</flux:table.column>
                <flux:table.column>Submitted</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($withdrawals as $withdrawal)
                    <flux:table.row wire:key="withdrawal-{{ $withdrawal->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="substr($withdrawal->wallet->user->name, 0, 2)" class="size-8" />
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $withdrawal->wallet->user->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $withdrawal->wallet->user->email }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="font-mono font-semibold text-red-500 text-base">-${{ number_format(abs($withdrawal->amount), 2) }}</span>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-600 dark:text-zinc-400 text-sm max-w-xs truncate">{{ $withdrawal->note ?? '—' }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">{{ $withdrawal->created_at->diffForHumans() }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="primary" icon="check" wire:click="approve({{ $withdrawal->id }})" wire:confirm="Approve this withdrawal?">
                                    Approve
                                </flux:button>
                                <flux:button size="sm" variant="danger" icon="x-mark" wire:click="reject({{ $withdrawal->id }})" wire:confirm="Reject this withdrawal?">
                                    Reject
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="p-4 bg-zinc-100 dark:bg-zinc-800 rounded-full">
                                    <flux:icon name="arrow-up-tray" class="size-8 text-zinc-400" />
                                </div>
                                <flux:heading size="md">All Clear!</flux:heading>
                                <flux:text class="text-zinc-500">No pending withdrawals to review.</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
