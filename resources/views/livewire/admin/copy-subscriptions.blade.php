<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Copy Subscriptions</flux:heading>
            <flux:text class="text-zinc-500">Credit profit or loss for active copy-trading subscriptions.</flux:text>
        </div>
    </div>

    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Trader</flux:table.column>
                <flux:table.column>Allocated</flux:table.column>
                <flux:table.column>Net P&amp;L</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($subscriptions as $sub)
                    <flux:table.row wire:key="sub-{{ $sub->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="substr($sub->user->name, 0, 2)" class="size-8" />
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $sub->user->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $sub->user->email }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-600 dark:text-zinc-300">{{ $sub->trader->name }}</flux:table.cell>
                        <flux:table.cell class="font-mono">${{ number_format($sub->amount, 2) }}</flux:table.cell>
                        <flux:table.cell class="font-mono {{ $sub->netPnl() >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $sub->netPnl() >= 0 ? '+' : '-' }}${{ number_format(abs($sub->netPnl()), 2) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="primary" icon="arrow-trending-up" wire:click="openCreditModal({{ $sub->id }}, 'profit')">
                                    Credit Profit
                                </flux:button>
                                <flux:button size="sm" variant="danger" icon="arrow-trending-down" wire:click="openCreditModal({{ $sub->id }}, 'loss')">
                                    Credit Loss
                                </flux:button>
                                <flux:button size="sm" variant="outline" wire:click="stopSubscription({{ $sub->id }})" wire:confirm="Stop this subscription?">
                                    Stop
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="p-4 bg-zinc-100 dark:bg-zinc-800 rounded-full">
                                    <flux:icon name="user-group" class="size-8 text-zinc-400" />
                                </div>
                                <flux:heading size="md">No active copy subscriptions</flux:heading>
                                <flux:text class="text-zinc-500">No users are currently copying a trader.</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="credit-pnl-modal" class="max-w-md md:min-w-md" wire:model="showCreditModal">
        <div class="space-y-6">
            <flux:heading size="lg">Credit {{ $creditType === 'profit' ? 'Profit' : 'Loss' }}</flux:heading>

            <flux:input wire:model="creditAmount" label="Amount (USD)" type="number" step="0.01" placeholder="0.00" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeCreditModal">Cancel</flux:button>
                <flux:button variant="{{ $creditType === 'profit' ? 'primary' : 'danger' }}" wire:click="creditPnl">
                    Credit {{ $creditType === 'profit' ? 'Profit' : 'Loss' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
