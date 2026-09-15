<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Notifications</flux:heading>
            <flux:text class="text-zinc-500">Everything that needs your attention.</flux:text>
        </div>

        @if ($unreadCount > 0)
            <flux:button size="sm" variant="outline" wire:click="markAllRead">
                Mark {{ $unreadCount }} as read
            </flux:button>
        @endif
    </div>

    <flux:card class="trading-card !p-0 overflow-hidden">
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($notifications as $notification)
                <div wire:key="notif-{{ $notification->id }}"
                    class="flex items-start gap-3 px-6 py-4 {{ $notification->read_at ? '' : 'bg-accent/5' }}">
                    <span class="mt-1.5 size-2 rounded-full shrink-0 {{ $notification->read_at ? 'bg-transparent' : 'bg-accent' }}"></span>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</div>
                        <div class="text-sm text-zinc-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</div>
                        <div class="text-xs text-zinc-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @if (! $notification->read_at)
                        <flux:button size="sm" variant="ghost" wire:click="markRead('{{ $notification->id }}')">
                            Mark read
                        </flux:button>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center gap-2 text-center py-16">
                    <flux:icon name="bell" class="size-6 text-zinc-300 dark:text-zinc-700" />
                    <flux:text class="text-zinc-500">No notifications yet.</flux:text>
                </div>
            @endforelse
        </div>
    </flux:card>

    <div>{{ $notifications->links() }}</div>
</div>
