<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">KYC Verification</flux:heading>
            <flux:text class="text-zinc-500">Review and manage user identity verification requests.</flux:text>
        </div>
        <div class="flex items-center gap-3">
            <flux:select wire:model.live="statusFilter" class="max-w-[150px]">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </flux:select>
            <flux:input wire:model.live.debounce.400ms="search" placeholder="Search name or email..." class="max-w-xs" icon="magnifying-glass" />
        </div>
    </div>

    <flux:card class="trading-card p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns class="bg-zinc-50 dark:bg-zinc-950">
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Document Type</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Submitted At</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($kycApplications as $application)
                    <flux:table.row wire:key="app-{{ $application->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :initials="$application->initials()" class="size-8" />
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white text-sm">{{ $application->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $application->email }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="capitalize">
                            {{ str_replace('_', ' ', $application->kyc_document_type) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($application->kyc_status === 'pending')
                                <flux:badge color="sky" size="sm">Pending</flux:badge>
                            @elseif ($application->kyc_status === 'approved')
                                <flux:badge color="green" size="sm">Approved</flux:badge>
                            @elseif ($application->kyc_status === 'rejected')
                                <flux:badge color="red" size="sm" title="{{ $application->kyc_rejection_reason }}">Rejected</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">
                            {{ $application->kyc_submitted_at ? $application->kyc_submitted_at->format('M j, Y H:i') : '-' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                @if($application->kyc_document_path)
                                    <flux:button size="sm" variant="outline" href="{{ route('admin.kyc.document', $application->id) }}" target="_blank">
                                        View Doc
                                    </flux:button>
                                @endif
                                @if($application->kyc_status === 'pending')
                                    <flux:button size="sm" variant="primary" icon="check" wire:click="approve({{ $application->id }})">
                                        Approve
                                    </flux:button>
                                    <flux:button size="sm" variant="danger" icon="x-mark" wire:click="openRejectModal({{ $application->id }})">
                                        Reject
                                    </flux:button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-zinc-500 py-8">
                            No KYC applications found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <div>{{ $kycApplications->links() }}</div>

    <flux:modal name="reject-kyc-modal" class="max-w-md md:min-w-md" wire:model="showRejectModal">
        <div class="space-y-6">
            <flux:heading size="lg">Reject KYC Application</flux:heading>

            <flux:input wire:model="rejectionReason" label="Reason for rejection" placeholder="e.g. Document image is too blurry." />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeRejectModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="reject">
                    Reject Application
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
