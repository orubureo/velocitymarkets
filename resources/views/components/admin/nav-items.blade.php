@props(['items'])

@foreach ($items as $item)
    @if (isset($item['children']))
        @php
            $hasActiveChild = collect($item['children'])->contains(fn ($child) => request()->routeIs($child['route']));
        @endphp
        <div x-data="{ open: {{ $hasActiveChild ? 'true' : 'false' }} }">
            <button type="button" x-on:click="open = !open"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 w-full whitespace-nowrap
                    {{ $hasActiveChild ? 'text-accent' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <flux:icon :name="$item['icon']" :variant="$hasActiveChild ? 'solid' : 'outline'" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span class="flex-1 text-left">{{ $item['label'] }}</span>
                <flux:icon name="chevron-down" variant="outline" class="size-4 shrink-0 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-cloak class="flex flex-col gap-0.5 mt-0.5 pl-[2.375rem]">
                @foreach ($item['children'] as $child)
                    @php $isChildCurrent = request()->routeIs($child['route']); @endphp
                    <a href="{{ route($child['route']) }}" wire:navigate
                        class="relative flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200 whitespace-nowrap
                            {{ $isChildCurrent ? 'bg-accent/10 text-accent' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                        @if ($isChildCurrent)
                            <span class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-4 w-1 rounded-r-full bg-accent animate-glow-pulse"></span>
                        @endif
                        <span class="size-1.5 rounded-full shrink-0 {{ $isChildCurrent ? 'bg-accent' : 'bg-zinc-300 dark:bg-zinc-600' }}"></span>
                        <span>{{ $child['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        @php $isCurrent = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}" wire:navigate
            class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                {{ $isCurrent ? 'bg-accent/10 text-accent' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
            @if ($isCurrent)
                <span class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-accent animate-glow-pulse"></span>
            @endif
            <flux:icon :name="$item['icon']" :variant="$isCurrent ? 'solid' : 'outline'" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
            <span>{{ $item['label'] }}</span>
        </a>
    @endif
@endforeach
