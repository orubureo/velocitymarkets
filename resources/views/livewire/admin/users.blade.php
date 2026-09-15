<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Users</flux:heading>
            <flux:text class="text-zinc-500">View platform users and manage their accounts.</flux:text>
        </div>
        <flux:input wire:model.live.debounce.400ms="search" placeholder="Search name or email…" class="max-w-xs" icon="magnifying-glass" />
    </div>

    <flux:card class="trading-card p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Country</flux:table.column>
                <flux:table.column>Registered</flux:table.column>
                <flux:table.column>Manage</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($users as $user)
                    <flux:table.row wire:key="user-{{ $user->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="$user->initials()" class="size-8" />
                                <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $user->name }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">{{ $user->email }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">{{ $user->country ?? '—' }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">{{ $user->created_at->format('M j, Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button size="sm" variant="outline" icon="user" :href="route('admin.users.show', $user)" wire:navigate>
                                Manage
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-zinc-500 py-8">
                            No users found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <div>{{ $users->links() }}</div>
</div>
