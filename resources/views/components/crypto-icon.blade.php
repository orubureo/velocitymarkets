@props(['currency', 'url' => null])

@php
    $class = $attributes->get('class', 'size-8');
@endphp

@if ($url)
    <span class="{{ $class }} rounded-full overflow-hidden shrink-0 inline-flex bg-zinc-100 dark:bg-zinc-800">
        <img src="{{ $url }}" alt="{{ $currency }}" loading="lazy" class="w-full h-full object-cover"
            onerror="cryptoIconFallback(this, '{{ $currency }}')">
    </span>
@else
    @switch(strtoupper($currency))
    @case('BTC')
        <svg viewBox="0 0 32 32" class="{{ $class }}" xmlns="http://www.w3.org/2000/svg">
            <circle cx="16" cy="16" r="16" fill="#F7931A" />
            <path fill="#fff" d="M22.1 14.2c.3-2-1.2-3.1-3.3-3.8l.7-2.7-1.6-.4-.7 2.6c-.4-.1-.9-.2-1.3-.3l.7-2.6-1.6-.4-.7 2.7c-.4-.1-.7-.2-1-.2l-2.2-.6-.4 1.7s1.2.3 1.2.3c.7.2.8.6.8.9l-.8 3.2c0 0 .1 0 .2.1h-.2l-1.1 4.5c-.1.2-.3.5-.8.4 0 0-1.2-.3-1.2-.3l-.8 1.8 2.1.5c.4.1.8.2 1.1.3l-.7 2.7 1.6.4.7-2.7c.5.1.9.2 1.3.3l-.7 2.7 1.6.4.7-2.7c2.7.5 4.7.3 5.6-2.1.7-2-.03-3.1-1.5-3.9 1.1-.2 1.9-1 2.1-2.5zm-3.6 5.1c-.5 2-3.9.9-5 .6l.9-3.6c1.1.3 4.6.8 4.1 3zm.5-5.1c-.5 1.8-3.3.9-4.2.7l.8-3.3c.9.2 3.9.6 3.4 2.6z"/>
        </svg>
        @break

    @case('ETH')
        <svg viewBox="0 0 32 32" class="{{ $class }}" xmlns="http://www.w3.org/2000/svg">
            <circle cx="16" cy="16" r="16" fill="#627EEA" />
            <g fill="#fff">
                <path fill-opacity=".8" d="M16.5 4v9.3l7.9 3.5z"/>
                <path d="M16.5 4 8.6 16.8l7.9-3.5z"/>
                <path fill-opacity=".8" d="M16.5 21.9v6.1l7.9-11z"/>
                <path d="M16.5 28v-6.1l-7.9-4.9z"/>
                <path fill-opacity=".6" d="M16.5 20.4l7.9-4.6-7.9-3.5z"/>
                <path fill-opacity=".9" d="M8.6 15.8l7.9 4.6v-8.1z"/>
            </g>
        </svg>
        @break

    @case('USDT')
        <svg viewBox="0 0 32 32" class="{{ $class }}" xmlns="http://www.w3.org/2000/svg">
            <circle cx="16" cy="16" r="16" fill="#26A17B" />
            <path fill="#fff" d="M17.9 17.4v-.01c-.11.01-.68.04-1.94.04-1.01 0-1.72-.03-1.97-.04v.02c-3.9-.17-6.8-.85-6.8-1.66 0-.81 2.9-1.49 6.8-1.67v2.66c.25.02.98.06 1.99.06 1.2 0 1.8-.05 1.92-.06v-2.65c3.89.17 6.78.85 6.78 1.66 0 .81-2.89 1.48-6.78 1.65zm0-3.6v-2.39h5.42V7.5H8.72v3.91h5.42v2.39c-4.4.2-7.72 1.07-7.72 2.12 0 1.05 3.32 1.91 7.72 2.12v7.58h3.76v-7.58c4.39-.2 7.7-1.07 7.7-2.12 0-1.05-3.31-1.91-7.7-2.12z"/>
        </svg>
        @break

    @case('SOL')
        <svg viewBox="0 0 32 32" class="{{ $class }}" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="sol-grad-{{ $currency }}" x1="4" y1="24" x2="28" y2="8" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#9945FF"/>
                    <stop offset="1" stop-color="#14F195"/>
                </linearGradient>
            </defs>
            <circle cx="16" cy="16" r="16" fill="#0B0B14" />
            <g fill="url(#sol-grad-{{ $currency }})">
                <path d="M9.2 19.9a.9.9 0 0 1 .6-.25h13.6a.45.45 0 0 1 .32.77l-2.7 2.7a.9.9 0 0 1-.63.26H6.8a.45.45 0 0 1-.32-.77z"/>
                <path d="M9.2 9.1a.93.93 0 0 1 .63-.25h13.6c.4 0 .6.49.32.77l-2.7 2.7a.93.93 0 0 1-.63.26H6.82a.45.45 0 0 1-.32-.77z"/>
                <path d="M20.8 14.45a.93.93 0 0 0-.63-.25H6.57a.45.45 0 0 0-.32.77l2.7 2.7c.17.17.4.26.63.26h13.6a.45.45 0 0 0 .32-.77z"/>
            </g>
        </svg>
        @break

        @default
            <div class="{{ $class }} rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-[10px] font-bold text-zinc-600 dark:text-zinc-300">
                {{ strtoupper(substr($currency, 0, 3)) }}
            </div>
    @endswitch
@endif
