<flux:dropdown position="bottom" align="end">
    <div class="relative inline-flex">
        <flux:button size="sm" variant="ghost" icon="bell" aria-label="Notifications" class="transition-transform duration-200 hover:scale-105" />

        @if ($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[17px] h-[17px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none ring-2 ring-white dark:ring-zinc-900 pointer-events-none">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </div>

    <flux:menu class="w-80">
        <div class="flex items-center justify-between px-2 py-2">
            <flux:heading size="sm">{{ __('Notifications') }}</flux:heading>
            @if ($unreadCount > 0)
                <button type="button" wire:click="markAllRead" class="text-xs font-semibold text-accent hover:underline">
                    Mark {{ $unreadCount }} as read
                </button>
            @endif
        </div>
        <flux:menu.separator />

        <div class="max-h-96 overflow-y-auto -mx-1 px-1">
            @forelse ($notifications as $notification)
                <div wire:key="notif-{{ $notification->id }}"
                    class="flex items-start gap-3 px-2 py-2.5 rounded-lg transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/60 {{ $notification->read_at ? '' : 'bg-accent/5' }}">
                    <div class="mt-0.5 rounded-full p-1.5 shrink-0 {{ $notification->read_at ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' : 'bg-accent/10 text-accent' }}">
                        <flux:icon name="bell" class="size-3.5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $notification->data['title'] ?? 'Notification' }}</div>
                        <div class="text-xs text-zinc-500 mt-0.5 line-clamp-2">{{ $notification->data['message'] ?? '' }}</div>
                        <div class="text-[11px] text-zinc-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @if (! $notification->read_at)
                        <span class="mt-1.5 size-2 rounded-full bg-accent shrink-0"></span>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center gap-2 text-center py-8">
                    <flux:icon name="bell" class="size-6 text-zinc-300 dark:text-zinc-700" />
                    <flux:text size="sm" class="text-zinc-500">{{ __('No new notifications') }}</flux:text>
                </div>
            @endforelse
        </div>

        <flux:menu.separator />

        <flux:menu.item as="a" href="{{ route('admin.notifications') }}" wire:navigate class="justify-center text-accent font-semibold">
            {{ __('View all notifications') }}
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
