<div class="flex flex-col gap-8 stagger-children">

    {{-- Page Header --}}
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Refer Friends &amp; Earn</flux:heading>
        <flux:text class="text-zinc-500">Invite others to join the {{ config('app.name') }} community</flux:text>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Left Column (2/3 width) --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Share Your Referral Link Card --}}
            <flux:card class="trading-card flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand">
                        <flux:icon name="share" class="size-5" />
                    </div>
                    <flux:heading size="lg">Share Your Referral Link</flux:heading>
                </div>

                <flux:text class="text-zinc-500">
                    Share your unique referral link with friends and earn rewards when they join:
                </flux:text>

                {{-- Referral Link Input + Copy Button --}}
                <div
                    x-data="{ copied: false }"
                    x-on:copy-referral-link.window="
                        navigator.clipboard.writeText('{{ $referralLink }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                    class="flex items-center gap-2"
                >
                    <flux:input
                        readonly
                        value="{{ $referralLink }}"
                        class="flex-1 font-mono text-sm bg-zinc-50 dark:bg-zinc-900"
                    />
                    <flux:button
                        wire:click="copyLink"
                        variant="primary"
                        class="shrink-0"
                    >
                        <span x-show="!copied" class="flex items-center gap-1.5">
                            <flux:icon name="clipboard-document" class="size-4" />
                            Copy
                        </span>
                        <span x-show="copied" x-cloak class="flex items-center gap-1.5">
                            <flux:icon name="check" class="size-4" />
                            Copied!
                        </span>
                    </flux:button>
                </div>

                {{-- Referral ID & Sponsor Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 flex flex-col gap-1">
                        <flux:text size="sm" class="text-zinc-500">Your Referral ID</flux:text>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-teal-600 dark:text-teal-400 font-semibold text-sm">{{ $referralCode }}</span>
                            <button
                                type="button"
                                x-data
                                x-on:click="navigator.clipboard.writeText('{{ $referralCode }}')"
                                class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors"
                                title="Copy Referral ID"
                            >
                                <flux:icon name="clipboard-document" class="size-4" />
                            </button>
                        </div>
                    </div>

                    <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 flex flex-col gap-1">
                        <flux:text size="sm" class="text-zinc-500">Your Sponsor</flux:text>
                        <div class="flex items-center gap-2 mt-1">
                            <flux:icon name="user" class="size-4 text-zinc-400" />
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $sponsor }}</span>
                        </div>
                    </div>
                </div>

                {{-- How Referrals Work --}}
                <div>
                    <flux:heading size="base" class="mb-4">How Referrals Work</flux:heading>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">

                        <div class="flex flex-col items-center gap-2">
                            <div class="size-12 rounded-full bg-sky-500/10 flex items-center justify-center">
                                <flux:icon name="link" class="size-5 text-sky-500" />
                            </div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Share Your Link</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Send your unique referral link to friends</flux:text>
                        </div>

                        <div class="flex flex-col items-center gap-2">
                            <div class="size-12 rounded-full bg-violet-500/10 flex items-center justify-center">
                                <flux:icon name="user-plus" class="size-5 text-violet-500" />
                            </div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Friends Sign Up</flux:text>
                            <flux:text size="sm" class="text-zinc-500">They register and become your referral</flux:text>
                        </div>

                        <div class="flex flex-col items-center gap-2">
                            <div class="size-12 rounded-full bg-amber-500/10 flex items-center justify-center">
                                <flux:icon name="gift" class="size-5 text-amber-500" />
                            </div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Earn Rewards</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Receive benefits when they invest</flux:text>
                        </div>

                    </div>
                </div>
            </flux:card>

        </div>

        {{-- Right Column (1/3 width) — Statistics --}}
        <div class="flex flex-col gap-4">
            <flux:card class="trading-card flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand">
                        <flux:icon name="chart-bar" class="size-5" />
                    </div>
                    <flux:heading size="lg">Referral Statistics</flux:heading>
                </div>

                {{-- Total Referrals --}}
                <div class="flex flex-col gap-1 pb-4 border-b border-zinc-100 dark:border-zinc-700">
                    <flux:text size="sm" class="text-zinc-500">Total Referrals</flux:text>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalReferrals }}</span>
                        <flux:badge size="sm" color="zinc">Users</flux:badge>
                    </div>
                </div>

                {{-- Referral Earnings --}}
                <div class="flex flex-col gap-1">
                    <flux:text size="sm" class="text-zinc-500">Referral Earnings</flux:text>
                    <span class="text-3xl font-bold font-mono text-zinc-900 dark:text-white mt-1">
                        ${{ number_format($referralEarnings, 2) }}
                    </span>
                </div>
            </flux:card>
        </div>

    </div>

    {{-- Your Referrals Table (Full Width) --}}
    <flux:card class="trading-card !p-0 overflow-hidden flex flex-col gap-4">
        <div class="flex items-center gap-3 px-6 pt-6">
            <div class="stat-icon-brand">
                <flux:icon name="user-group" class="size-5" />
            </div>
            <flux:heading size="lg">Your Referrals</flux:heading>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Client Name</flux:table.column>
                <flux:table.column>Ref. Level</flux:table.column>
                <flux:table.column>Parent</flux:table.column>
                <flux:table.column>Client Status</flux:table.column>
                <flux:table.column>Date Registered</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($referrals as $referral)
                    <flux:table.row wire:key="ref-{{ $referral->id }}">
                        <flux:table.cell class="font-medium">
                            {{ $referral->name }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="zinc">Level 1</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500">
                            {{ auth()->user()->name }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($referral->email_verified_at)
                                <flux:badge size="sm" color="lime">Verified</flux:badge>
                            @else
                                <flux:badge size="sm" color="sky">Pending</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500">
                            {{ $referral->created_at->format('M j, Y') }}
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-zinc-500 py-8">
                            <div class="flex flex-col items-center gap-2">
                                <flux:icon name="user-group" class="size-8 text-zinc-300" />
                                <span>No referrals yet. Start sharing your link!</span>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

</div>
