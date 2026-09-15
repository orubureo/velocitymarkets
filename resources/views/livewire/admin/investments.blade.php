<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Investments</flux:heading>
            <flux:text class="text-zinc-500">Credit ROI payouts for active user investments.</flux:text>
        </div>
    </div>

    <flux:card class="p-0 overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Plan</flux:table.column>
                <flux:table.column>Amount</flux:table.column>
                <flux:table.column>Started</flux:table.column>
                <flux:table.column>Ends</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($investments as $investment)
                    <flux:table.row wire:key="investment-{{ $investment->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="substr($investment->user->name, 0, 2)" class="size-8" />
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $investment->user->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $investment->user->email }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-600 dark:text-zinc-300">{{ $investment->plan->name }}</flux:table.cell>
                        <flux:table.cell class="font-mono">${{ number_format($investment->amount, 2) }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">{{ $investment->starts_at?->format('M j, Y') }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">{{ $investment->ends_at?->format('M j, Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="primary" icon="banknotes" wire:click="openCreditModal({{ $investment->id }})">
                                    Credit ROI
                                </flux:button>
                                <flux:button size="sm" variant="outline" icon="check" wire:click="markCompleted({{ $investment->id }})" wire:confirm="Mark this investment as completed?">
                                    Complete
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="p-4 bg-zinc-100 dark:bg-zinc-800 rounded-full">
                                    <flux:icon name="briefcase" class="size-8 text-zinc-400" />
                                </div>
                                <flux:heading size="md">No active investments</flux:heading>
                                <flux:text class="text-zinc-500">No users currently have an active investment plan.</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="credit-roi-modal" class="max-w-md md:min-w-md" wire:model="showCreditModal">
        <div class="space-y-6">
            <flux:heading size="lg">Credit ROI Payout</flux:heading>

            <flux:input wire:model="creditAmount" label="Amount (USD)" type="number" step="0.01" placeholder="0.00" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeCreditModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="creditRoi">Credit Payout</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
