<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <div class="min-h-screen flex">
            {{-- Left Branding Panel --}}
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-zinc-900 dark:bg-zinc-950 relative overflow-hidden">
                {{-- Decorative grid --}}
                <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(#ffae11 1px, transparent 1px), linear-gradient(90deg, #ffae11 1px, transparent 1px); background-size: 40px 40px;"></div>
                
                {{-- Glows --}}
                <div class="absolute -top-32 -left-32 w-[500px] h-[500px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(212,136,12,0.2) 0%, transparent 70%);"></div>
                <div class="absolute -bottom-40 -right-20 w-[400px] h-[400px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(255,174,17,0.1) 0%, transparent 70%);"></div>

                {{-- Logo --}}
                <div class="relative z-10">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', 'VelocityMarkets') }}" class="h-8 w-auto">
                    </a>
                </div>

                {{-- Hero Copy --}}
                <div class="relative z-10">
                    <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                        Trade smarter, <br>
                        <span class="text-transparent bg-clip-text" style="background-image: linear-gradient(135deg, #ffae11, #d4880c);">grow your portfolio</span>
                    </h1>
                    <p class="text-zinc-400 text-base max-w-sm leading-relaxed">
                        Join thousands of traders worldwide. Access global markets, automated investment plans, and copy trading all from one platform.
                    </p>
                </div>
                
                {{-- Market stats --}}
                <div class="relative z-10 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-bold text-white">0%</p>
                        <p class="text-zinc-500 text-xs mt-1">Trading Commission</p>
                    </div>
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-bold text-white">$50M+</p>
                        <p class="text-zinc-500 text-xs mt-1">Daily Volume</p>
                    </div>
                </div>
            </div>
            
            {{-- Right Form Panel --}}
            <div class="flex-1 flex flex-col items-center justify-center p-6 bg-zinc-50 dark:bg-zinc-950 relative">
                <div class="w-full max-w-md">
                    {{-- Mobile Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-8 lg:hidden justify-center">
                        <x-app-logo-icon class="size-8" />
                        <span class="text-zinc-900 dark:text-white font-bold text-xl">{{ config('app.name', 'VelocityMarkets') }}</span>
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
