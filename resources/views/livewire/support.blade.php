<div class="flex flex-col gap-8 stagger-children">

    {{-- Page Header --}}
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Support</flux:heading>
        <flux:text class="text-zinc-500">Get help with your account, deposits, withdrawals or trading</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('status') }}
        </flux:callout>
    @endif

    @php
        $categories = \App\Models\SupportTicket::CATEGORIES;

        $faqs = [
            [
                'q' => 'How do I get my account verified?',
                'a' => 'Submit a government-issued ID and a proof of address from your dashboard to complete identity verification (KYC). Verification is required before you can withdraw funds, and reviews are usually completed within a day.',
            ],
            [
                'q' => 'How do deposits work?',
                'a' => "From your Wallet, choose the currency and network and we'll generate a unique deposit address for you. Always double-check you're sending on the matching network — a deposit sent on the wrong network cannot be recovered.",
            ],
            [
                'q' => 'How do withdrawals work?',
                'a' => 'Request a withdrawal from your Wallet with your destination address. Requests are reviewed for security and are typically processed within 24 hours, and a verified account is required before a withdrawal can be released.',
            ],
            [
                'q' => 'How does a support ticket get handled?',
                'a' => "Submit a ticket with the category and details of your issue and our team will review it from here. You can track its status — open, in progress or resolved — right on this page, and urgent account or security issues are always prioritized.",
            ],
        ];
    @endphp

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Left Column (2/3 width) --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Submit a Ticket Card --}}
            <flux:card class="trading-card flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand">
                        <flux:icon name="lifebuoy" class="size-5" />
                    </div>
                    <flux:heading size="lg">Submit a Ticket</flux:heading>
                </div>

                <flux:text class="text-zinc-500">
                    Tell us what's going on and our team will get back to you.
                </flux:text>

                <div class="flex flex-col gap-4">
                    <flux:input wire:model="subject" label="Subject" placeholder="Briefly describe your issue" maxlength="150" />

                    <flux:select wire:model="category" label="Category">
                        @foreach ($categories as $value => $label)
                            <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:textarea wire:model="message" label="Message" rows="5" placeholder="Share as much detail as you can — what happened, when, and anything you've already tried." maxlength="2000" />

                    <div class="flex justify-end">
                        <flux:button wire:click="submitTicket" variant="primary" icon="paper-airplane">
                            Submit Ticket
                        </flux:button>
                    </div>
                </div>
            </flux:card>

            {{-- Your Tickets Card --}}
            <flux:card class="trading-card !p-0 overflow-hidden flex flex-col gap-4">
                <div class="flex items-center gap-3 px-6 pt-6">
                    <div class="stat-icon-brand">
                        <flux:icon name="ticket" class="size-5" />
                    </div>
                    <flux:heading size="lg">Your Tickets</flux:heading>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Subject</flux:table.column>
                        <flux:table.column>Category</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Submitted</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($tickets as $ticket)
                            <flux:table.row wire:key="ticket-{{ $ticket->id }}">
                                <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                                    {{ $ticket->subject }}
                                    @if ($ticket->admin_response)
                                        <flux:text size="xs" class="text-zinc-500 mt-1 block font-normal">
                                            <flux:icon name="chat-bubble-left-right" class="size-3 inline -mt-0.5" />
                                            Response: {{ $ticket->admin_response }}
                                        </flux:text>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="text-zinc-500">
                                    {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <x-status-badge :status="$ticket->status" />
                                </flux:table.cell>
                                <flux:table.cell class="text-zinc-500">
                                    {{ $ticket->created_at->format('M j, Y') }}
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center text-zinc-500 py-8">
                                    <div class="flex flex-col items-center gap-2">
                                        <flux:icon name="lifebuoy" class="size-8 text-zinc-300" />
                                        <span>No support tickets yet.</span>
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>

        </div>

        {{-- Right Column (1/3 width) --}}
        <div class="flex flex-col gap-6">

            {{-- What We Can Help With --}}
            <flux:card class="trading-card flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <div class="stat-icon-brand">
                        <flux:icon name="hand-raised" class="size-5" />
                    </div>
                    <flux:heading size="lg">What We Can Help With</flux:heading>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-3">
                        <div class="size-12 rounded-full bg-sky-500/10 flex items-center justify-center shrink-0">
                            <flux:icon name="identification" class="size-5 text-sky-500" />
                        </div>
                        <div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Account & Verification</flux:text>
                            <flux:text size="sm" class="text-zinc-500">KYC, profile details and login issues</flux:text>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-12 rounded-full bg-violet-500/10 flex items-center justify-center shrink-0">
                            <flux:icon name="banknotes" class="size-5 text-violet-500" />
                        </div>
                        <div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Deposits & Withdrawals</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Funding your account or requesting a payout</flux:text>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-12 rounded-full bg-amber-500/10 flex items-center justify-center shrink-0">
                            <flux:icon name="chart-bar-square" class="size-5 text-amber-500" />
                        </div>
                        <div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Trading</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Orders, positions and copy trading</flux:text>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-12 rounded-full bg-purple-500/10 flex items-center justify-center shrink-0">
                            <flux:icon name="chat-bubble-left-right" class="size-5 text-purple-500" />
                        </div>
                        <div>
                            <flux:text class="font-semibold text-zinc-900 dark:text-white text-sm">Other</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Anything else on your mind</flux:text>
                        </div>
                    </div>
                </div>
            </flux:card>

            {{-- Common Questions --}}
            <flux:card class="trading-card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="stat-icon-brand">
                        <flux:icon name="question-mark-circle" class="size-5" />
                    </div>
                    <flux:heading size="lg">Common Questions</flux:heading>
                </div>
                <div class="flex flex-col gap-2">
                    @foreach ($faqs as $faq)
                        <div x-data="{ open: false }" class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                            <button type="button" @click="open = !open"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <flux:text size="sm" class="font-semibold text-zinc-900 dark:text-white">{{ $faq['q'] }}</flux:text>
                                <flux:icon name="chevron-down" class="size-4 text-zinc-400 shrink-0 transition-transform duration-200" x-bind:class="open && 'rotate-180'" />
                            </button>
                            <div x-show="open" x-collapse x-cloak>
                                <flux:text size="sm" class="block text-zinc-500 px-4 pb-3">{{ $faq['a'] }}</flux:text>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>

        </div>

    </div>

</div>
