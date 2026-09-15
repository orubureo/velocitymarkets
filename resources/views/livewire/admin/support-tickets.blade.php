<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">Support Tickets</flux:heading>
            <flux:text class="text-zinc-500">Review and respond to user support requests.</flux:text>
        </div>
        <div class="flex items-center gap-3">
            <flux:select wire:model.live="statusFilter" class="max-w-[150px]">
                <option value="all">All Statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
            </flux:select>
            <flux:input wire:model.live.debounce.400ms="search" placeholder="Search subject, name or email..." class="max-w-xs" icon="magnifying-glass" />
        </div>
    </div>

    <flux:card class="trading-card p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Subject</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Submitted</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($tickets as $ticket)
                    <flux:table.row wire:key="ticket-{{ $ticket->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="$ticket->user->initials()" class="size-8" />
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $ticket->user->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $ticket->user->email }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="max-w-xs truncate">{{ $ticket->subject }}</flux:table.cell>
                        <flux:table.cell>
                            {{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? ucfirst($ticket->category) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($ticket->status === 'open')
                                <flux:badge color="sky" size="sm">Open</flux:badge>
                            @elseif ($ticket->status === 'in_progress')
                                <flux:badge color="amber" size="sm">In Progress</flux:badge>
                            @elseif ($ticket->status === 'resolved')
                                <flux:badge color="green" size="sm">Resolved</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">
                            {{ $ticket->created_at->format('M j, Y H:i') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:modal.trigger name="view-ticket-{{ $ticket->id }}">
                                    <flux:button size="sm" variant="outline">View</flux:button>
                                </flux:modal.trigger>

                                @if ($ticket->status === 'open')
                                    <flux:button size="sm" wire:click="markInProgress({{ $ticket->id }})">
                                        Mark In Progress
                                    </flux:button>
                                @endif

                                @if (in_array($ticket->status, ['open', 'in_progress']))
                                    <flux:button size="sm" variant="primary" wire:click="openRespondModal({{ $ticket->id }})">
                                        Respond & Resolve
                                    </flux:button>
                                @endif

                                @if ($ticket->status === 'resolved')
                                    <flux:text size="sm" class="text-zinc-400 italic">Resolved</flux:text>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center text-zinc-500 py-8">
                            No support tickets found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <div>{{ $tickets->links() }}</div>

    {{-- View ticket modals --}}
    @foreach ($tickets as $ticket)
        <flux:modal name="view-ticket-{{ $ticket->id }}" class="max-w-lg md:min-w-lg" wire:key="view-modal-{{ $ticket->id }}">
            <div class="flex flex-col gap-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <flux:heading size="lg">{{ $ticket->subject }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-500">
                            {{ $ticket->user->name }} &middot; {{ $ticket->user->email }}
                        </flux:text>
                    </div>
                    @if ($ticket->status === 'open')
                        <flux:badge color="sky" size="sm">Open</flux:badge>
                    @elseif ($ticket->status === 'in_progress')
                        <flux:badge color="amber" size="sm">In Progress</flux:badge>
                    @elseif ($ticket->status === 'resolved')
                        <flux:badge color="green" size="sm">Resolved</flux:badge>
                    @endif
                </div>

                <div class="flex items-center gap-3 text-xs text-zinc-500">
                    <span>{{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? ucfirst($ticket->category) }}</span>
                    <span>&middot;</span>
                    <span>Submitted {{ $ticket->created_at->format('M j, Y H:i') }}</span>
                </div>

                <div>
                    <flux:text size="sm" class="text-zinc-500 block mb-1">Message</flux:text>
                    <flux:text class="text-zinc-900 dark:text-white whitespace-pre-line">{{ $ticket->message }}</flux:text>
                </div>

                @if ($ticket->admin_response)
                    <div class="rounded-xl bg-zinc-50 dark:bg-zinc-800/50 p-4">
                        <flux:text size="sm" class="text-zinc-500 block mb-1">
                            Admin Response
                            @if ($ticket->responded_at)
                                &middot; {{ $ticket->responded_at->format('M j, Y H:i') }}
                            @endif
                        </flux:text>
                        <flux:text class="text-zinc-900 dark:text-white whitespace-pre-line">{{ $ticket->admin_response }}</flux:text>
                    </div>
                @endif

                <div class="flex justify-end">
                    <flux:modal.close>
                        <flux:button variant="outline">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </flux:modal>
    @endforeach

    {{-- Respond & resolve modal --}}
    <flux:modal name="respond-ticket-modal" class="max-w-md md:min-w-md" wire:model="showRespondModal">
        @php
            $respondingTicket = $tickets->firstWhere('id', $respondingTicketId);
        @endphp
        <div class="space-y-6">
            <flux:heading size="lg">Respond & Resolve</flux:heading>

            @if ($respondingTicket)
                <div class="rounded-xl bg-zinc-50 dark:bg-zinc-800/50 p-4">
                    <flux:text class="font-medium text-zinc-900 dark:text-white text-sm block">{{ $respondingTicket->subject }}</flux:text>
                    <flux:text size="sm" class="text-zinc-500 mt-1 block whitespace-pre-line">{{ $respondingTicket->message }}</flux:text>
                </div>
            @endif

            <flux:textarea wire:model="response" label="Your response" rows="5" placeholder="Let the user know how their issue was resolved..." maxlength="2000" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeRespondModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="submitResponse">
                    Send Response & Resolve
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
