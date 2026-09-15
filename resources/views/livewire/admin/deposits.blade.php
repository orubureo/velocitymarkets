<div>
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="text-zinc-900 dark:text-white">Deposits</flux:heading>
                <flux:text class="text-zinc-500">Review and approve pending deposit requests.</flux:text>
            </div>
            <flux:badge color="sky" size="lg">Pending Review</flux:badge>
        </div>

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
                    @forelse ($deposits as $deposit)
                        <flux:table.row wire:key="deposit-{{ $deposit->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <flux:avatar :initials="substr($deposit->wallet->user->name, 0, 2)" class="size-8" />
                                    <div>
                                        <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $deposit->wallet->user->name }}</div>
                                        <div class="text-xs text-zinc-500">{{ $deposit->wallet->user->email }}</div>
                                    </div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <span class="font-mono font-semibold text-green-500 text-base">+${{ number_format($deposit->amount, 2) }}</span>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-600 dark:text-zinc-400 text-sm max-w-xs truncate">{{ $deposit->note ?? '—' }}</flux:table.cell>
                            <flux:table.cell class="text-zinc-500 text-sm">{{ $deposit->created_at->diffForHumans() }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-2">
                                    @if ($deposit->proof_path)
                                        <flux:button size="sm" variant="outline" icon="paper-clip" href="{{ route('admin.deposits.proof', $deposit) }}" target="_blank">
                                            Proof
                                        </flux:button>
                                    @endif
                                    <flux:button size="sm" variant="primary" icon="check" wire:click="approve({{ $deposit->id }})" wire:confirm="Approve this deposit?">
                                        Approve
                                    </flux:button>
                                    <flux:button size="sm" variant="danger" icon="x-mark" wire:click="reject({{ $deposit->id }})" wire:confirm="Reject this deposit?">
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
                                        <flux:icon name="banknotes" class="size-8 text-zinc-400" />
                                    </div>
                                    <flux:heading size="md">All Clear!</flux:heading>
                                    <flux:text class="text-zinc-500">No pending deposits to review.</flux:text>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</div>
