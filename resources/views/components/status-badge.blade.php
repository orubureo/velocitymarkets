@props(['status'])

@php
    // Plain colored pill — no icons. Approved/completed and pending both get the
    // same live pulsing-dot treatment (they're both "alive" states, just different
    // colors); approved additionally gets a soft glow since it settled successfully.
    // Rejected is a flat dead-end — no animation.
    $badge = match ($status) {
        'pending' => [
            'label' => 'Pending',
            'classes' => 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-500/20',
            'dot' => 'bg-yellow-500',
        ],
        'open' => [
            'label' => 'Open',
            'classes' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20',
            'dot' => 'bg-sky-500',
        ],
        'in_progress' => [
            'label' => 'In Progress',
            'classes' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20',
            'dot' => 'bg-amber-500',
        ],
        'approved', 'completed', 'resolved' => [
            'label' => ucfirst($status),
            'classes' => 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20 badge-glow-green',
            'dot' => 'bg-green-500',
        ],
        'rejected' => [
            'label' => 'Rejected',
            'classes' => 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20',
            'dot' => null,
        ],
        default => [
            'label' => ucfirst($status),
            'classes' => 'bg-zinc-500/10 text-zinc-600 dark:text-zinc-400 border border-zinc-500/20',
            'dot' => null,
        ],
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0', $badge['classes']]) }}>
    @if ($badge['dot'])
        <span class="relative flex size-1.5">
            <span class="absolute inline-flex h-full w-full rounded-full {{ $badge['dot'] }} pulse-dot-ping"></span>
            <span class="relative inline-flex size-1.5 rounded-full {{ $badge['dot'] }}"></span>
        </span>
    @endif
    {{ $badge['label'] }}
</span>
