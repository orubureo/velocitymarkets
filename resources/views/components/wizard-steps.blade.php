@props(['labels' => [], 'current' => 1])

<div class="flex items-center rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 px-4 py-3.5 sm:px-5">
    @foreach ($labels as $label)
        @php $n = $loop->iteration; $isDone = $current > $n; $isActive = $current === $n; @endphp
        <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="relative flex items-center justify-center size-8 rounded-full text-xs font-bold border-2 transition-all duration-300
                    {{ $isDone
                        ? 'bg-teal-500 border-teal-500 text-white'
                        : ($isActive
                            ? 'border-teal-500 text-teal-500 bg-teal-500/10 shadow-[0_0_0_4px_rgba(20,184,166,0.12)]'
                            : 'border-zinc-200 dark:border-zinc-700 text-zinc-400 dark:text-zinc-500') }}">
                    @if ($isDone)
                        <flux:icon name="check" class="size-4" />
                    @else
                        {{ $n }}
                    @endif
                    @if ($isActive)
                        <span class="absolute inset-0 rounded-full border-2 border-teal-500 animate-ping opacity-40"></span>
                    @endif
                </div>
                <span class="text-sm font-semibold whitespace-nowrap hidden sm:inline transition-colors duration-300
                    {{ $isActive || $isDone ? 'text-zinc-900 dark:text-white' : 'text-zinc-400 dark:text-zinc-500' }}">
                    {{ $label }}
                </span>
            </div>

            @if (! $loop->last)
                <div class="flex-1 h-0.5 mx-3 rounded-full bg-zinc-200 dark:bg-zinc-800 overflow-hidden">
                    <div class="h-full bg-teal-500 rounded-full transition-all duration-500 ease-out" style="width: {{ $isDone ? '100' : '0' }}%"></div>
                </div>
            @endif
        </div>
    @endforeach
</div>
