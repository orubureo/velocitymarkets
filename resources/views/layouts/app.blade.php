<x-layouts::app.sidebar :title="$title ?? null">
    <div class="flex-1 p-6 max-lg:pb-24 overflow-auto">
        {{ $slot }}

        <!-- Mobile Dock -->
        <div class="fixed bottom-0 left-0 z-40 w-full h-16 bg-white border-t border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 lg:hidden pb-safe">
            <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
                <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('dashboard') ? 'text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="home" variant="{{ request()->routeIs('dashboard') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Home') }}</span>
                </a>
                <a href="{{ route('trade') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('trade') ? 'text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="chart-bar-square" variant="{{ request()->routeIs('trade') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Trade') }}</span>
                </a>
                <a href="{{ route('copy-trading') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('copy-trading') ? 'text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="sparkles" variant="{{ request()->routeIs('copy-trading') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Copy') }}</span>
                </a>
                <a href="{{ route('wallet') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('wallet') ? 'text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="wallet" variant="{{ request()->routeIs('wallet') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Wallet') }}</span>
                </a>
                <button type="button" x-data x-on:click="$dispatch('flux-sidebar-toggle')" class="inline-flex flex-col items-center justify-center px-5 hover:bg-zinc-50 dark:hover:bg-zinc-800 group text-zinc-500 dark:text-zinc-400">
                    <flux:icon name="bars-3" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('More') }}</span>
                </button>
            </div>
        </div>
    </div>
</x-layouts::app.sidebar>
