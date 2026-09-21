@props(['tier' => null])

<span {{ $attributes->class([
    'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold shrink-0',
    $tier ? 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20' : 'bg-zinc-500/10 text-zinc-500 dark:text-zinc-400 border border-zinc-500/20',
]) }}>
    @if ($tier)
        <flux:icon name="star" variant="solid" class="size-2.5" />
    @endif
    {{ $tier->name ?? 'Free' }}
</span>
