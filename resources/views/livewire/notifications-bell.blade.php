@php
    $position = $variant === 'compact' ? 'right' : 'top';
@endphp

<flux:dropdown position="{{ $position }}" align="start" class="{{ $variant === 'mobile' ? 'w-full' : '' }}">
    <div class="relative inline-flex">
        @if ($variant === 'mobile')
            <flux:button variant="ghost" icon="bell" class="w-full justify-start" aria-label="Notifications">
                {{ __('Notifications') }}
            </flux:button>
        @else
            <flux:button size="sm" variant="ghost" icon="bell" aria-label="Notifications" />
        @endif

        @if ($unreadCount > 0)
            <span class="absolute {{ $variant === 'mobile' ? 'left-5 top-1' : 'top-0 right-0' }} flex items-center justify-center min-w-[16px] h-[16px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none pointer-events-none">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </div>

    <flux:menu class="w-80">
        <div class="flex items-center justify-between px-3 py-2">
            <span class="text-sm font-medium text-zinc-500">{{ __('Notifications') }}</span>
            @if ($unreadCount > 0)
                <button type="button" wire:click="markAllRead" class="text-xs font-medium text-teal-600 dark:text-teal-400 hover:underline">
                    Mark {{ $unreadCount }} as read
                </button>
            @endif
        </div>
        <flux:menu.separator />

        @forelse ($notifications as $notification)
            <div wire:key="notif-{{ $notification->id }}" class="flex items-start gap-2.5 px-3 py-2.5 {{ $notification->read_at ? '' : 'bg-teal-500/5' }}">
                <span class="mt-1.5 size-1.5 rounded-full shrink-0 {{ $notification->read_at ? 'bg-transparent' : 'bg-teal-500' }}"></span>
                <div class="min-w-0">
                    <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</div>
                    <div class="text-xs text-zinc-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </div>
        @empty
            <div class="px-3 py-4 text-sm text-center text-zinc-500">
                {{ __('No new notifications') }}
            </div>
        @endforelse
    </flux:menu>
</flux:dropdown>
