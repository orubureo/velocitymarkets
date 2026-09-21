<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="theme-admin min-h-screen bg-zinc-50 dark:bg-zinc-950 flex">
    @php
        $adminNavItems = [
            ['icon' => 'squares-2x2', 'route' => 'admin.dashboard', 'label' => __('Dashboard')],
            ['icon' => 'users', 'route' => 'admin.users', 'label' => __('Manage Users')],
            ['icon' => 'identification', 'route' => 'admin.kyc', 'label' => __('KYC Verification')],
            ['icon' => 'lifebuoy', 'route' => 'admin.support-tickets', 'label' => __('Support Tickets')],
            ['icon' => 'banknotes', 'route' => 'admin.deposits', 'label' => __('Deposits')],
            ['icon' => 'arrow-up-tray', 'route' => 'admin.withdrawals', 'label' => __('Withdrawals')],
            ['icon' => 'chart-bar', 'route' => 'admin.markets', 'label' => __('Markets')],
            ['icon' => 'briefcase', 'route' => 'admin.investment-plans', 'label' => __('Investment Plans')],
            ['icon' => 'star', 'route' => 'admin.account-tiers', 'label' => __('Account Tiers')],
            ['icon' => 'bolt', 'route' => 'admin.signal-tiers', 'label' => __('Signal Tiers')],
            ['icon' => 'signal', 'route' => 'admin.signals', 'label' => __('Signals')],
            ['icon' => 'user-group', 'route' => 'admin.traders', 'label' => __('Copy Experts')],
            ['icon' => 'wallet', 'route' => 'admin.crypto-wallets', 'label' => __('Crypto Wallets')],
            ['icon' => 'chart-pie', 'route' => 'admin.investments', 'label' => __('Investments')],
            ['icon' => 'arrow-trending-up', 'route' => 'admin.copy-subscriptions', 'label' => __('Copy Subscriptions')],
        ];
    @endphp

    {{-- Desktop sidebar: nav scrolls independently, logo header and theme/profile footer stay fixed --}}
    <div class="hidden lg:flex flex-col shrink-0 sticky top-0 h-screen w-64 z-10 border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="flex flex-col gap-3 px-4 py-4 border-b border-zinc-200 dark:border-zinc-800 shrink-0 overflow-hidden bg-gradient-to-br from-accent/[0.08] via-accent/[0.03] to-transparent dark:from-accent/15 dark:via-accent/5 dark:to-transparent">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2.5 min-w-0">
                <div class="flex items-center justify-center size-9 rounded-xl bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-700 shrink-0">
                    <x-app-logo-icon class="size-5" />
                </div>
                <span class="font-bold tracking-tight text-zinc-900 dark:text-white whitespace-nowrap">
                    {{ config('app.name', 'VelocityMarkets') }}
                </span>
            </a>

            <span class="inline-flex items-center gap-1.5 w-fit px-3 py-1 rounded-full text-xs font-semibold bg-accent text-accent-foreground shadow-sm">
                <flux:icon name="shield-check" class="size-3.5" />
                {{ __('Admin Panel') }}
            </span>
        </div>

        <nav class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden py-4 flex flex-col gap-0.5 px-3">
            @foreach ($adminNavItems as $item)
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
            @endforeach
        </nav>

        <div class="shrink-0 border-t border-zinc-200 dark:border-zinc-800 p-3 bg-zinc-50/60 dark:bg-zinc-950/40">
            <flux:radio.group x-data variant="segmented" class="w-full" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun" aria-label="Light" title="Light" />
                <flux:radio value="dark" icon="moon" aria-label="Dark" title="Dark" />
                <flux:radio value="system" icon="computer-desktop" aria-label="System" title="System" />
            </flux:radio.group>
        </div>
    </div>

    {{-- Mobile sidebar (Flux handles the drawer/backdrop behavior). Deliberately
         no `sticky` prop — that scrolls the WHOLE sidebar (header + nav + theme
         switcher) as one unit once it overflows. Instead the sidebar is a
         fixed-height flex column (overflow-hidden) with only the middle nav
         block scrolling, matching the desktop sidebar: logo pinned at top,
         links scroll in between, theme switcher pinned at the bottom. --}}
    <flux:sidebar collapsible="mobile"
        class="lg:hidden max-h-dvh overflow-hidden border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <flux:sidebar.header>
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2 min-w-0">
                <flux:icon name="shield-check" class="size-5 text-accent shrink-0" />
                <span class="font-bold text-zinc-900 dark:text-white whitespace-nowrap">{{ __('Admin Panel') }}</span>
            </a>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain">
        <flux:sidebar.nav>
            <div class="grid gap-0.5">
                @foreach ($adminNavItems as $item)
                    @php $isCurrent = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}" wire:navigate
                        class="group relative flex items-center gap-3.5 px-3 py-3 rounded-xl text-base font-bold transition-all duration-200 whitespace-nowrap
                            {{ $isCurrent ? 'bg-accent/10 text-accent' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-50' }}">
                        @if ($isCurrent)
                            <span class="nav-indicator absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-accent animate-glow-pulse"></span>
                        @endif
                        <flux:icon :name="$item['icon']" :variant="$isCurrent ? 'solid' : 'outline'" class="size-6 shrink-0" />
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </flux:sidebar.nav>
        </div>

        <flux:sidebar.nav>
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun" aria-label="Light" title="Light" />
                <flux:radio value="dark" icon="moon" aria-label="Dark" title="Dark" />
                <flux:radio value="system" icon="computer-desktop" aria-label="System" title="System" />
            </flux:radio.group>
        </flux:sidebar.nav>

        {{-- Desktop admin user menu --}}
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="Auth::guard('admin')->user()->name"
                :initials="Auth::guard('admin')->user()->initials()" icon-trailing="chevrons-up-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="Auth::guard('admin')->user()->name"
                                :initials="Auth::guard('admin')->user()->initials()" />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ Auth::guard('admin')->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ Auth::guard('admin')->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    {{-- Main content area --}}
    <div class="flex flex-col flex-1 min-w-0 min-h-screen">

        {{-- Mobile top bar --}}
        <div class="lg:hidden sticky top-0 z-10 flex items-center h-14 px-4 shrink-0 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <button type="button" x-data x-on:click="$dispatch('flux-sidebar-toggle')"
                aria-label="{{ __('Open menu') }}"
                class="flex items-center justify-center size-11 -ml-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                <flux:icon name="bars-3" class="size-6" />
            </button>

            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex-1 flex items-center justify-center gap-2">
                <x-app-logo-icon class="size-6" />
                <span class="text-base font-semibold text-zinc-900 dark:text-white whitespace-nowrap">{{ config('app.name', 'VelocityMarkets') }}</span>
            </a>

            <flux:dropdown position="bottom" align="end">
                <button type="button" class="rounded-full transition-all duration-200 hover:scale-105 hover:ring-2 hover:ring-accent/30">
                    <flux:avatar :src="Auth::guard('admin')->user()->avatarUrl()" :name="Auth::guard('admin')->user()->name"
                        :initials="Auth::guard('admin')->user()->initials()" size="sm" circle
                        class="ring-2 ring-zinc-200 dark:ring-zinc-700" />
                </button>

                <flux:menu class="w-64">
                    <div class="flex items-center gap-3 px-2 py-2.5">
                        <flux:avatar :src="Auth::guard('admin')->user()->avatarUrl()" :name="Auth::guard('admin')->user()->name"
                            :initials="Auth::guard('admin')->user()->initials()" size="lg" circle
                            class="ring-2 ring-accent/20 shrink-0" />
                        <div class="grid flex-1 min-w-0 text-start leading-tight">
                            <flux:heading class="truncate">{{ Auth::guard('admin')->user()->name }}</flux:heading>
                            <flux:text size="sm" class="truncate text-zinc-500">{{ Auth::guard('admin')->user()->email }}</flux:text>
                            <span class="mt-1 inline-flex items-center gap-1 w-fit px-2 py-0.5 rounded-full text-[11px] font-semibold bg-accent/10 text-accent">
                                Administrator
                            </span>
                        </div>
                    </div>

                    <flux:menu.separator />

                    <flux:menu.item as="a" icon="cog" href="{{ route('admin.settings') }}" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            variant="danger" class="w-full cursor-pointer">
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>

        {{-- Desktop top bar --}}
        <div class="hidden lg:flex sticky top-0 z-20 items-center justify-between gap-2 h-20 px-6 shrink-0 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            @php
                $adminGreeting = match (true) {
                    now()->hour < 12 => __('Good morning'),
                    now()->hour < 17 => __('Good afternoon'),
                    default => __('Good evening'),
                };
            @endphp
            <div class="min-w-0">
                <div class="font-semibold text-zinc-900 dark:text-white truncate">
                    {{ $adminGreeting }}, {{ Auth::guard('admin')->user()->name }}
                </div>
                <flux:text size="sm" class="text-zinc-500">{{ now()->format('l, F j, Y') }}</flux:text>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <livewire:admin.notifications-bell />

                <flux:dropdown position="bottom" align="end">
                <button type="button" class="rounded-full transition-all duration-200 hover:scale-105 hover:ring-2 hover:ring-accent/30">
                    <flux:avatar :src="Auth::guard('admin')->user()->avatarUrl()" :name="Auth::guard('admin')->user()->name"
                        :initials="Auth::guard('admin')->user()->initials()" size="sm" circle
                        class="ring-2 ring-zinc-200 dark:ring-zinc-700" />
                </button>

                <flux:menu class="w-64">
                    <div class="flex items-center gap-3 px-2 py-2.5">
                        <flux:avatar :src="Auth::guard('admin')->user()->avatarUrl()" :name="Auth::guard('admin')->user()->name"
                            :initials="Auth::guard('admin')->user()->initials()" size="lg" circle
                            class="ring-2 ring-accent/20 shrink-0" />
                        <div class="grid flex-1 min-w-0 text-start leading-tight">
                            <flux:heading class="truncate">{{ Auth::guard('admin')->user()->name }}</flux:heading>
                            <flux:text size="sm" class="truncate text-zinc-500">{{ Auth::guard('admin')->user()->email }}</flux:text>
                            <span class="mt-1 inline-flex items-center gap-1 w-fit px-2 py-0.5 rounded-full text-[11px] font-semibold bg-accent/10 text-accent">
                                Administrator
                            </span>
                        </div>
                    </div>

                    <flux:menu.separator />

                    <flux:menu.item as="a" icon="cog" href="{{ route('admin.settings') }}" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            variant="danger" class="w-full cursor-pointer">
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            </div>
        </div>

        <div class="p-6 lg:p-8 max-lg:pb-24">
            {{ $slot }}
        </div>

        {{-- Mobile dock --}}
        <div class="fixed bottom-0 left-0 z-10 w-full h-16 bg-white border-t border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 lg:hidden pb-safe">
            <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('admin.dashboard') ? 'text-accent' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="squares-2x2" variant="{{ request()->routeIs('admin.dashboard') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('admin.deposits') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('admin.deposits') ? 'text-accent' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="banknotes" variant="{{ request()->routeIs('admin.deposits') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Deposits') }}</span>
                </a>
                <a href="{{ route('admin.withdrawals') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('admin.withdrawals') ? 'text-accent' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="arrow-up-tray" variant="{{ request()->routeIs('admin.withdrawals') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Withdrawals') }}</span>
                </a>
                <a href="{{ route('admin.notifications') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 group {{ request()->routeIs('admin.notifications') ? 'text-accent' : 'text-zinc-500 dark:text-zinc-400' }}">
                    <flux:icon name="bell" variant="{{ request()->routeIs('admin.notifications') ? 'solid' : 'outline' }}" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('Alerts') }}</span>
                </a>
                <button type="button" x-data x-on:click="$dispatch('flux-sidebar-toggle')" class="inline-flex flex-col items-center justify-center px-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 group text-zinc-500 dark:text-zinc-400">
                    <flux:icon name="bars-3" class="size-6 mb-1" />
                    <span class="text-[10px]">{{ __('More') }}</span>
                </button>
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
