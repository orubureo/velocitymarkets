<div class="flex flex-col gap-6 stagger-children">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Users</flux:heading>
            <flux:text class="text-zinc-500">View platform users and manage their accounts.</flux:text>
        </div>
        <flux:input wire:model.live.debounce.400ms="search" placeholder="Search name or email…" class="max-w-xs" icon="magnifying-glass" />
    </div>

    @php
        $kycBadge = fn (string $status) => match ($status) {
            'approved' => ['color' => 'green', 'label' => 'Verified'],
            'pending' => ['color' => 'sky', 'label' => 'Pending'],
            'rejected' => ['color' => 'red', 'label' => 'Rejected'],
            default => ['color' => 'zinc', 'label' => 'Not Submitted'],
        };
    @endphp

    <flux:card class="trading-card p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column class="hidden md:table-cell">Email</flux:table.column>
                <flux:table.column class="hidden lg:table-cell">Country</flux:table.column>
                <flux:table.column class="hidden md:table-cell">Status</flux:table.column>
                <flux:table.column class="hidden md:table-cell">Registered</flux:table.column>
                <flux:table.column>Manage</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($users as $user)
                    @php $kyc = $kycBadge($user->kyc_status); @endphp
                    <flux:table.row wire:key="user-{{ $user->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="$user->initials()" color="auto" class="size-8 shrink-0" />
                                <div class="min-w-0">
                                    <div class="font-medium text-zinc-900 dark:text-white text-sm truncate">{{ $user->name }}</div>
                                    <x-account-tier-badge :tier="$user->accountTier" class="mt-0.5" />
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm hidden md:table-cell">{{ $user->email }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm hidden lg:table-cell">{{ $user->country ?? '—' }}</flux:table.cell>
                        <flux:table.cell class="hidden md:table-cell">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <flux:badge size="sm" variant="solid" color="{{ $kyc['color'] }}">{{ $kyc['label'] }}</flux:badge>
                                @if ($user->is_blocked)
                                    <flux:badge size="sm" variant="solid" color="red">Blocked</flux:badge>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm hidden md:table-cell">{{ $user->created_at->format('M j, Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button size="sm" variant="primary" color="teal" icon="user" :href="route('admin.users.show', $user)" wire:navigate>
                                Manage
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center text-zinc-500 py-8">
                            No users found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <div>{{ $users->links() }}</div>
</div>
