<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex">
    {{-- Desktop Collapsible Sidebar --}}
    <div
        x-data="{ collapsed: $persist(false).as('sidebar-collapsed') }"
        x-bind:class="collapsed ? 'w-[64px]' : 'w-64'"
        class="hidden lg:block relative shrink-0 sticky top-0 h-screen transition-[width] duration-300 ease-in-out z-10"
    >
        <div class="relative flex flex-col h-full w-full overflow-hidden border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        {{-- Header: Logo (centered when collapsed) --}}
        <div class="flex items-center h-14 border-b border-zinc-200 dark:border-zinc-800 shrink-0"
            x-bind:class="collapsed ? 'justify-center px-0' : 'justify-start px-4'">
            <a href="{{ route('dashboard') }}" wire:navigate
                class="group flex items-center gap-2.5 overflow-hidden min-w-0"
                x-bind:title="collapsed ? 'VelocityMarkets' : ''">
                <x-app-logo-icon class="size-7 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                    class="font-semibold tracking-tight bg-gradient-to-r from-zinc-900 to-zinc-600 dark:from-white dark:to-zinc-400 bg-clip-text text-transparent whitespace-nowrap">
                    VelocityMarkets
                </span>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 flex flex-col gap-0.5 px-3">

            {{-- Overview group heading --}}
            <span x-show="!collapsed"
                class="flex items-center gap-1.5 px-2.5 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 whitespace-nowrap">
                <span class="size-1 rounded-full bg-teal-500/60"></span>
                Overview
            </span>

            <a href="{{ route('dashboard') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Dashboard') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('dashboard') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('dashboard') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="home" variant="{{ request()->routeIs('dashboard') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Dashboard') }}
                </span>
            </a>

            <a href="{{ route('news') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('News') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('news') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('news') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="newspaper" variant="{{ request()->routeIs('news') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('News') }}
                </span>
            </a>

            {{-- Assets group heading --}}
            <span x-show="!collapsed"
                class="flex items-center gap-1.5 px-2.5 pt-5 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 whitespace-nowrap">
                <span class="size-1 rounded-full bg-teal-500/60"></span>
                Assets
            </span>
            <flux:separator x-show="collapsed" class="my-2" />

            <a href="{{ route('wallet') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Wallet') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('wallet') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('wallet') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="wallet" variant="{{ request()->routeIs('wallet') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Wallet') }}
                </span>
            </a>

            <a href="{{ route('transactions') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Transactions') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('transactions') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('transactions') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="clock" variant="{{ request()->routeIs('transactions') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Transactions') }}
                </span>
            </a>

            {{-- Investing group heading --}}
            <span x-show="!collapsed"
                class="flex items-center gap-1.5 px-2.5 pt-5 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 whitespace-nowrap">
                <span class="size-1 rounded-full bg-teal-500/60"></span>
                Investing
            </span>
            <flux:separator x-show="collapsed" class="my-2" />

            <a href="{{ route('trade') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Markets & Trading') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('trade*') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('trade*') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="chart-bar-square" variant="{{ request()->routeIs('trade*') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Markets & Trading') }}
                </span>
            </a>

            <a href="{{ route('copy-trading') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Copy Trading') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('copy-trading') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('copy-trading') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="sparkles" variant="{{ request()->routeIs('copy-trading') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Copy Trading') }}
                </span>
            </a>

            <a href="{{ route('investment.plans') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Investment Plans') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('investment.plans') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('investment.plans') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="briefcase" variant="{{ request()->routeIs('investment.plans') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Investment Plans') }}
                </span>
            </a>

            <a href="{{ route('upgrade-account') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Account Upgrade') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('upgrade-account') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('upgrade-account') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="star" variant="{{ request()->routeIs('upgrade-account') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Account Upgrade') }}
                </span>
            </a>

            <a href="{{ route('buy-signal') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Buy Signal') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('buy-signal') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('buy-signal') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="bolt" variant="{{ request()->routeIs('buy-signal') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Buy Signal') }}
                </span>
            </a>

            {{-- Services group heading --}}
            <span x-show="!collapsed"
                class="flex items-center gap-1.5 px-2.5 pt-5 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 whitespace-nowrap">
                <span class="size-1 rounded-full bg-teal-500/60"></span>
                Services
            </span>
            <flux:separator x-show="collapsed" class="my-2" />

            <a href="{{ route('referral') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Referral') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('referral') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('referral') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="user-group" variant="{{ request()->routeIs('referral') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Referral') }}
                </span>
            </a>

            <a href="{{ route('support') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Support') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('support') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('support') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="lifebuoy" variant="{{ request()->routeIs('support') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Support') }}
                </span>
            </a>

            <a href="{{ route('profile.edit') }}" wire:navigate
                x-bind:title="collapsed ? '{{ __('Settings') }}' : ''"
                class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 hover:translate-x-0.5 whitespace-nowrap
                    {{ request()->routeIs('profile.edit') ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                <span x-show="{{ request()->routeIs('profile.edit') ? 'true' : 'false' }}"
                    class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                <flux:icon name="cog" variant="{{ request()->routeIs('profile.edit') ? 'solid' : 'outline' }}" class="size-6 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0">
                    {{ __('Settings') }}
                </span>
            </a>
        </nav>

        {{-- Bottom: Notifications, theme switcher, account --}}
        <div class="shrink-0 border-t border-zinc-200 dark:border-zinc-800 p-3 flex flex-col gap-3 bg-zinc-50/60 dark:bg-zinc-950/40">
            <div x-show="!collapsed" class="flex items-center gap-2">
                <livewire:notifications-bell />
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="flex-1">
                    <flux:radio value="light" icon="sun" aria-label="Light" title="Light" />
                    <flux:radio value="dark" icon="moon" aria-label="Dark" title="Dark" />
                    <flux:radio value="system" icon="computer-desktop" aria-label="System" title="System" />
                </flux:radio.group>
            </div>
            <div x-show="collapsed" x-data class="flex flex-col items-center gap-2">
                <livewire:notifications-bell variant="compact" />
                <flux:button size="sm" variant="ghost" icon="sun" x-show="$flux.appearance === 'light'"
                    x-on:click="$flux.appearance = 'dark'" title="Switch to dark" />
                <flux:button size="sm" variant="ghost" icon="moon" x-show="$flux.appearance !== 'light'"
                    x-on:click="$flux.appearance = 'light'" title="Switch to light" />
            </div>

            <flux:dropdown position="top" align="start" class="w-full">
                <button type="button"
                    class="flex items-center gap-2 w-full rounded-xl px-2 py-2 hover:bg-white dark:hover:bg-zinc-800 border border-transparent hover:border-zinc-200 dark:hover:border-zinc-700 transition-colors"
                    x-bind:class="collapsed ? 'justify-center' : ''">
                    <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" class="ring-2 ring-white dark:ring-zinc-900" />
                    <div x-show="!collapsed" class="flex-1 min-w-0 text-left">
                        <div class="text-sm font-medium truncate text-zinc-900 dark:text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</div>
                    </div>
                    <flux:icon x-show="!collapsed" name="chevron-up" class="size-4 text-zinc-400 shrink-0" />
                </button>

                <flux:menu class="!p-2 !min-w-0 w-56">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-test="logout-button"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2.5 transition-colors">
                            <flux:icon name="arrow-right-start-on-rectangle" class="size-4" />
                            {{ __('Log out') }}
                        </button>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
        </div>

        {{-- Floating collapse/expand toggle: sits half on/half off the sidebar's
             edge so it never competes for space with the logo when collapsed.
             Lives outside the overflow-hidden box above so it isn't clipped. --}}
        <button
            type="button"
            x-on:click="collapsed = !collapsed"
            x-bind:title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
            class="absolute top-4 -right-3 z-20 flex items-center justify-center size-6 rounded-full border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-100 hover:scale-110 active:scale-95 shadow-sm transition-all duration-200"
        >
            <flux:icon x-show="!collapsed" x-transition:enter="transition-transform duration-200" x-transition:enter-start="-rotate-90 opacity-0" x-transition:enter-end="rotate-0 opacity-100" name="chevron-left" class="size-3.5" />
            <flux:icon x-show="collapsed" x-transition:enter="transition-transform duration-200" x-transition:enter-start="rotate-90 opacity-0" x-transition:enter-end="rotate-0 opacity-100" name="chevron-right" class="size-3.5" />
        </button>
    </div>

    {{-- Mobile sidebar (Flux handles this). Deliberately no `sticky` prop —
         that makes Flux scroll the WHOLE sidebar (header + nav + account) as
         one unit once it overflows. Instead the sidebar itself is a fixed-
         height flex column (overflow-hidden) with only the middle nav block
         scrolling, matching the desktop sidebar: logo pinned at top, links
         scroll in between, account/sign-out pinned at the bottom. --}}
    <flux:sidebar collapsible="mobile"
        class="lg:hidden max-h-dvh overflow-hidden border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain flex flex-col gap-4">
        <flux:sidebar.nav>
            @php
                $mobileNavGroups = [
                    __('Overview') => [
                        ['icon' => 'home', 'route' => 'dashboard', 'label' => __('Dashboard')],
                        ['icon' => 'newspaper', 'route' => 'news', 'label' => __('News')],
                    ],
                    __('Assets') => [
                        ['icon' => 'wallet', 'route' => 'wallet', 'label' => __('Wallet')],
                        ['icon' => 'clock', 'route' => 'transactions', 'label' => __('Transactions')],
                    ],
                    __('Investing') => [
                        ['icon' => 'chart-bar-square', 'route' => 'trade', 'label' => __('Markets & Trading'), 'activePattern' => 'trade*'],
                        ['icon' => 'sparkles', 'route' => 'copy-trading', 'label' => __('Copy Trading')],
                        ['icon' => 'briefcase', 'route' => 'investment.plans', 'label' => __('Investment Plans')],
                        ['icon' => 'star', 'route' => 'upgrade-account', 'label' => __('Account Upgrade')],
                        ['icon' => 'bolt', 'route' => 'buy-signal', 'label' => __('Buy Signal')],
                    ],
                    __('Services') => [
                        ['icon' => 'user-group', 'route' => 'referral', 'label' => __('Referral')],
                        ['icon' => 'lifebuoy', 'route' => 'support', 'label' => __('Support')],
                        ['icon' => 'cog', 'route' => 'profile.edit', 'label' => __('Settings')],
                    ],
                ];
            @endphp

            @foreach ($mobileNavGroups as $heading => $items)
                <flux:sidebar.group :heading="$heading" class="grid gap-0.5">
                    @foreach ($items as $item)
                        @php $isCurrent = request()->routeIs($item['activePattern'] ?? $item['route']); @endphp
                        <a href="{{ route($item['route']) }}" wire:navigate
                            class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 whitespace-nowrap
                                {{ $isCurrent ? 'bg-teal-500/10 text-teal-700 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                            @if ($isCurrent)
                                <span class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500 animate-glow-pulse"></span>
                            @endif
                            <flux:icon :name="$item['icon']" :variant="$isCurrent ? 'solid' : 'outline'" class="size-6 shrink-0" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </flux:sidebar.group>
            @endforeach
        </flux:sidebar.nav>
        </div>

        <flux:sidebar.nav>
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun" aria-label="Light" title="Light" />
                <flux:radio value="dark" icon="moon" aria-label="Dark" title="Dark" />
                <flux:radio value="system" icon="computer-desktop" aria-label="System" title="System" />
            </flux:radio.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:dropdown position="top" align="start" class="w-full">
                <button type="button" class="flex items-center gap-2 w-full rounded-lg px-2 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                    <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" />
                    <div class="flex-1 min-w-0 text-left">
                        <div class="text-sm font-medium truncate text-zinc-900 dark:text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</div>
                    </div>
                    <flux:icon name="chevron-up" class="size-4 text-zinc-400 shrink-0" />
                </button>

                <flux:menu class="!p-2 !min-w-0 w-56">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-test="logout-button"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2.5 transition-colors">
                            <flux:icon name="arrow-right-start-on-rectangle" class="size-4" />
                            {{ __('Log out') }}
                        </button>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar.nav>
    </flux:sidebar>

    <!-- Main content area: fills remaining width next to sidebar -->
    <div class="flex flex-col flex-1 min-w-0 min-h-screen">

    {{-- Mobile top bar --}}
    <div class="lg:hidden sticky top-0 z-10 flex items-center h-16 px-4 shrink-0 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="flex-1 flex items-center">
            <button type="button" x-data x-on:click="$dispatch('flux-sidebar-toggle')"
                x-bind:aria-label="'{{ __('Open menu') }}'"
                class="flex items-center justify-center size-11 -ml-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                <flux:icon name="bars-3" class="size-7" />
            </button>
        </div>

        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 shrink-0">
            <x-app-logo-icon class="size-8" />
            <span class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-white whitespace-nowrap">VelocityMarkets</span>
        </a>

        <div class="flex-1 flex items-center justify-end">
            <livewire:notifications-bell variant="compact" />
        </div>
    </div>

    @if (session()->has('impersonating_admin_id'))
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium">
            <div class="flex items-center gap-2">
                <flux:icon name="eye" class="size-4 shrink-0" />
                <span>Viewing as {{ auth()->user()->name }} — logged in by {{ session('impersonating_admin_name') }}</span>
            </div>
            <form method="POST" action="{{ route('impersonate.stop') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1 transition-colors">
                    <flux:icon name="arrow-uturn-left" class="size-3.5" />
                    Return to Admin
                </button>
            </form>
        </div>
    @endif

    @if (request()->routeIs('dashboard') || request()->routeIs('trade'))
        {{-- Market ticker: sticky so it stays pinned at the top of the actual
             window scroll (this page has no isolated inner scroll container),
             offset below the mobile top bar (h-14) so the two don't overlap.
             The component tries TradingView's live widget first and falls
             back to our own real-price ticker if it's blocked/fails to load.
             Scoped to `trade` (the markets list) only, not `trade.show` — the
             focused per-pair page already has its own chart/price display. --}}
        <livewire:market-ticker />
    @endif

    {{ $slot }}

    </div>{{-- end main content wrapper --}}

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    {{--

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tradeChart', (trades) => ({
                chart: null,

                init() {
                    const entries = trades.map(t => ({
                        x: new Date(t.created_at).getTime(),
                        y: parseFloat(t.entry_price),
                    }));

                    const exits = trades.filter(t => t.status !== 'open').map(t => ({
                        x: new Date(t.settled_at).getTime(),
                        y: parseFloat(t.exit_price),
                        won: t.status === 'won',
                    }));

                    this.chart = new Chart(this.$refs.canvas, {
                        type: 'line',
                        data: {
                            datasets: [
                                {
                                    label: 'Price',
                                    data: [],
                                    borderColor: '#0F6E56',
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    tension: 0.15,
                                },
                                {
                                    label: 'Entries',
                                    data: entries,
                                    type: 'scatter',
                                    pointStyle: 'triangle',
                                    radius: 6,
                                    backgroundColor: '#378ADD',
                                },
                                {
                                    label: 'Exits',
                                    data: exits,
                                    type: 'scatter',
                                    pointRadius: 6,
                                    backgroundColor: (ctx) => {
                                        const raw = ctx.raw;
                                        return raw && raw.won ? '#639922' : '#E24B4A';
                                    },
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            animation: false,
                            scales: {
                                x: { type: 'time', time: { unit: 'minute' } },
                                y: { ticks: { callback: (v) => '$' + v.toLocaleString() } },
                            },
                            plugins: { legend: { display: false } },
                        },
                    });

                    window.addEventListener('chart-tick', (e) => {
                        this.addTick(e.detail.price, e.detail.time);
                    });
                },

                addTick(price, time) {
                    const ds = this.chart.data.datasets[0].data;
                    ds.push({ x: time, y: price });
                    if (ds.length > 120) ds.shift();
                    this.chart.update('none');
                },
            }));

            Livewire.on('price-tick', ({ price, time }) => {
                window.dispatchEvent(new CustomEvent('chart-tick', { detail: { price, time } }));
            });
        });
    </script>
    @endpush

    @stack('scripts')
    --}}

    @stack('scripts')
    @fluxScripts
</body>

</html>