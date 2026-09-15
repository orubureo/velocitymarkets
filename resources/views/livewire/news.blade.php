<div class="flex flex-col gap-8 stagger-children">

    {{-- Page Header --}}
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">News</flux:heading>
            <flux:text class="text-zinc-500">Live crypto headlines syndicated from Cointelegraph's public feed.</flux:text>
        </div>

        <a href="https://cointelegraph.com" target="_blank" rel="noopener noreferrer"
            class="group flex items-center gap-1.5 text-sm font-semibold text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition-colors shrink-0">
            Cointelegraph
            <flux:icon name="arrow-top-right-on-square" class="size-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
        </a>
    </div>

    @if (empty($headlines))
        {{-- Honest empty/error state — never a fake placeholder article --}}
        <flux:card class="trading-card flex flex-col items-center gap-3 py-16 text-center">
            <div class="stat-icon-brand">
                <flux:icon name="newspaper" class="size-6" />
            </div>
            <flux:heading size="lg">Couldn't load the latest news right now</flux:heading>
            <flux:text class="text-zinc-500 max-w-sm">
                We couldn't reach the Cointelegraph feed. Please try again shortly.
            </flux:text>
        </flux:card>
    @else
        <div class="flex flex-col gap-4">
            @foreach ($headlines as $headline)
                <flux:card wire:key="headline-{{ md5($headline['link']) }}" class="trading-card flex gap-4 sm:gap-5 !p-4 sm:!p-5">

                    {{-- Thumbnail: real image from the feed, or a plain icon chip fallback --}}
                    <div class="relative shrink-0 size-20 sm:size-28 rounded-xl overflow-hidden bg-teal-500/10 flex items-center justify-center text-teal-500">
                        <flux:icon name="newspaper" class="size-7" />
                        @if ($headline['image'])
                            <img src="{{ $headline['image'] }}" alt="" loading="lazy"
                                class="absolute inset-0 w-full h-full object-cover"
                                onerror="this.style.display='none'">
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex flex-col gap-1.5 min-w-0 justify-center">
                        <flux:text size="sm" class="text-zinc-500">
                            {{ $headline['source'] }}
                            @if ($headline['published_at'])
                                &middot; {{ $headline['published_at']->diffForHumans() }}
                            @endif
                        </flux:text>

                        <a href="{{ $headline['link'] }}" target="_blank" rel="noopener noreferrer"
                            class="font-semibold text-zinc-900 dark:text-white hover:text-teal-600 dark:hover:text-teal-400 transition-colors leading-snug">
                            {{ $headline['title'] }}
                        </a>

                        @if ($headline['excerpt'])
                            <flux:text size="sm" class="text-zinc-500 line-clamp-2">
                                {{ $headline['excerpt'] }}
                            </flux:text>
                        @endif
                    </div>
                </flux:card>
            @endforeach
        </div>
    @endif

</div>
