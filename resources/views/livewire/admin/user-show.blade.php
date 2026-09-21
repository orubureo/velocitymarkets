<div class="flex flex-col gap-8">
    <flux:link variant="subtle" href="{{ route('admin.users') }}" wire:navigate class="inline-flex items-center gap-1.5 w-fit">
        <flux:icon name="arrow-left" class="size-4" />
        Users
    </flux:link>

    {{-- Identity --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0">
            <flux:avatar :name="$user->name" color="auto" size="xl" />
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white truncate">{{ $user->name }}</flux:heading>
                    @if ($user->is_blocked)
                        <flux:badge size="sm" color="red">Blocked</flux:badge>
                    @endif
                    @if ($user->withdrawals_paused)
                        <flux:badge size="sm" color="amber">Withdrawals Paused</flux:badge>
                    @endif
                </div>
                <flux:text class="text-zinc-500 truncate block" title="{{ $user->email }}">{{ $user->email }}</flux:text>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <x-status-badge :status="$user->kyc_status" />
            <flux:text size="sm" class="text-zinc-400">Joined {{ $user->created_at->format('M j, Y') }}</flux:text>

            <flux:dropdown position="bottom" align="end">
                <flux:button variant="primary" icon:trailing="chevron-down">Actions</flux:button>

                <flux:menu>
                    @if ($wallet)
                        <flux:menu.item icon="plus" wire:click="openAdjustModal('credit')">Credit Wallet</flux:menu.item>
                        <flux:menu.item icon="minus" wire:click="openAdjustModal('debit')">Debit Wallet</flux:menu.item>
                        <flux:menu.separator />
                    @endif

                    <flux:menu.item icon="pencil-square" wire:click="openEditModal">Edit Profile</flux:menu.item>
                    <flux:menu.separator />

                    <flux:menu.item
                        icon="{{ $user->is_blocked ? 'lock-open' : 'lock-closed' }}"
                        wire:click="toggleBlock"
                        wire:confirm="{{ $user->is_blocked ? 'Unblock this user? They will be able to log in again.' : 'Block this user? They will be immediately unable to log in.' }}"
                    >
                        {{ $user->is_blocked ? 'Unblock User' : 'Block User' }}
                    </flux:menu.item>
                    <flux:menu.item
                        icon="{{ $user->withdrawals_paused ? 'play' : 'pause' }}"
                        wire:click="togglePauseWithdrawals"
                        wire:confirm="{{ $user->withdrawals_paused ? 'Resume withdrawals for this user?' : 'Pause withdrawals for this user? They will be unable to submit new withdrawal requests.' }}"
                    >
                        {{ $user->withdrawals_paused ? 'Resume Withdrawals' : 'Pause Withdrawals' }}
                    </flux:menu.item>
                    <flux:menu.separator />

                    <flux:menu.item icon="key" wire:click="resetPassword" wire:confirm="Reset this user's password? Their current password will stop working immediately.">
                        Reset Password
                    </flux:menu.item>
                    @unless ($user->email_verified_at)
                        <flux:menu.item icon="check-badge" wire:click="verifyEmail" wire:confirm="Mark this user's email as verified?">
                            Verify Email
                        </flux:menu.item>
                    @endunless
                    <flux:menu.separator />

                    <flux:menu.item icon="arrow-right-end-on-rectangle" wire:click="loginAsUser" wire:confirm="Log in as {{ $user->name }}? You'll be viewing the platform as them until you return to admin.">
                        Login as User
                    </flux:menu.item>
                    <flux:menu.separator />

                    <flux:menu.item variant="danger" icon="trash" wire:click="confirmDelete">Delete User</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <flux:separator />

    {{-- Wallet --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <flux:card class="trading-card lg:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <flux:text size="sm" class="text-zinc-500 font-medium">Wallet Balance</flux:text>
                <div class="text-4xl font-bold font-mono tabular-nums text-zinc-900 dark:text-white mt-1">
                    ${{ number_format($wallet->balance ?? 0, 2) }}
                </div>
            </div>

            @unless ($wallet)
                <flux:text size="sm" class="text-zinc-500">No wallet on file</flux:text>
            @endunless
        </flux:card>

        <flux:card class="trading-card flex flex-col justify-center gap-4">
            <div class="flex items-center justify-between gap-4">
                <flux:text size="sm" class="text-zinc-500">Deposited</flux:text>
                <flux:text class="font-mono font-semibold text-green-600 dark:text-green-400">${{ number_format($totalDeposits, 2) }}</flux:text>
            </div>
            <div class="flex items-center justify-between gap-4">
                <flux:text size="sm" class="text-zinc-500">Withdrawn</flux:text>
                <flux:text class="font-mono font-semibold text-red-600 dark:text-red-400">${{ number_format($totalWithdrawals, 2) }}</flux:text>
            </div>
            <div class="flex items-center justify-between gap-4">
                <flux:text size="sm" class="text-zinc-500">Trades placed</flux:text>
                <flux:text class="font-mono font-semibold text-zinc-900 dark:text-white">{{ number_format($tradesCount) }}</flux:text>
            </div>
        </flux:card>
    </div>

    {{-- Details --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-8">
        <div>
            <flux:heading class="uppercase tracking-wide text-xs text-zinc-400 mb-4">Profile</flux:heading>
            <dl class="grid grid-cols-2 gap-y-4">
                <dt class="text-sm text-zinc-500">Full name</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $user->name }}</dd>

                <dt class="text-sm text-zinc-500">Email</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right truncate min-w-0" title="{{ $user->email }}">{{ $user->email }}</dd>

                <dt class="text-sm text-zinc-500">Phone</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $user->phone ?? '—' }}</dd>

                <dt class="text-sm text-zinc-500">Country</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $user->country ?? '—' }}</dd>
            </dl>
        </div>

        <div>
            <flux:heading class="uppercase tracking-wide text-xs text-zinc-400 mb-4">Account</flux:heading>
            <dl class="grid grid-cols-2 gap-y-4">
                <dt class="text-sm text-zinc-500">Referral code</dt>
                <dd class="text-sm font-mono font-medium text-zinc-900 dark:text-white text-right">{{ $user->referral_code ?? '—' }}</dd>

                <dt class="text-sm text-zinc-500">Referred by</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right truncate min-w-0">{{ $user->referrer?->name ?? '—' }}</dd>

                <dt class="text-sm text-zinc-500">Open tickets</dt>
                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $openTickets }}</dd>
            </dl>
        </div>
    </div>

    @if ($wallet)
        <flux:modal name="adjust-balance-modal" class="max-w-md md:min-w-md" wire:model="showAdjustModal">
            <div class="space-y-6">
                <flux:heading size="lg">{{ $adjustType === 'credit' ? 'Credit' : 'Debit' }} Wallet</flux:heading>

                <flux:select wire:model="adjustCategory" label="Category">
                    @foreach ($adjustCategories as $key => $category)
                        <flux:select.option value="{{ $key }}">{{ $category['label'] }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="adjustAmount" label="Amount (USD)" type="number" step="0.01" placeholder="0.00" />
                <flux:input wire:model="adjustNote" label="Note" placeholder="Reason for this adjustment" />

                <div class="flex gap-3 justify-end">
                    <flux:button variant="outline" wire:click="closeAdjustModal">Cancel</flux:button>
                    <flux:button variant="{{ $adjustType === 'credit' ? 'primary' : 'danger' }}" wire:click="adjustBalance">
                        {{ $adjustType === 'credit' ? 'Credit' : 'Debit' }} Wallet
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    <flux:modal name="edit-user-modal" class="max-w-md md:min-w-md" wire:model="showEditModal">
        <div class="space-y-6">
            <flux:heading size="lg">Edit Profile</flux:heading>

            <div class="grid grid-cols-1 gap-4">
                <flux:input wire:model="editName" label="Full name" />
                <flux:input wire:model="editEmail" label="Email address" type="email" />
                <flux:input wire:model="editPhone" label="Phone" />
                <flux:input wire:model="editCountry" label="Country" />
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeEditModal">Cancel</flux:button>
                <flux:button variant="primary" wire:click="saveEdit" wire:loading.attr="disabled" wire:target="saveEdit">
                    Save Changes
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="reset-password-modal" class="max-w-md md:min-w-md" wire:model="showResetPasswordModal">
        <div class="space-y-6" x-data="{ copied: false }">
            <div class="flex items-center gap-3">
                <div class="stat-icon-brand !rounded-full">
                    <flux:icon name="key" class="size-5" />
                </div>
                <flux:heading size="lg">Password Reset</flux:heading>
            </div>

            <flux:callout variant="warning" icon="exclamation-triangle">
                This is shown once. Copy it now and share it with the user through a secure channel — it won't be shown again.
            </flux:callout>

            <div class="flex gap-2">
                <flux:input readonly value="{{ $generatedPassword }}" class="font-mono text-sm bg-zinc-50 dark:bg-zinc-950" />
                <flux:button
                    x-on:click="navigator.clipboard.writeText('{{ $generatedPassword }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    icon="clipboard" variant="primary"
                >
                    <span x-show="!copied">Copy</span>
                    <span x-show="copied" x-cloak>Copied!</span>
                </flux:button>
            </div>

            <div class="flex justify-end">
                <flux:button variant="primary" wire:click="closeResetPasswordModal">Done</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="user-delete-modal" class="max-w-md md:min-w-md" wire:model="showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon-down !rounded-full">
                    <flux:icon name="exclamation-triangle" class="size-5" />
                </div>
                <flux:heading size="lg">Delete "{{ $user->name }}"?</flux:heading>
            </div>

            <flux:callout variant="danger" icon="exclamation-triangle">
                @php
                    $nonZeroCounts = collect($deletingRelatedCounts)->filter();
                @endphp
                @if ($nonZeroCounts->isNotEmpty())
                    This user has
                    {{ $nonZeroCounts->map(fn ($count, $label) => "{$count} ".Str::plural($label, $count))->join(', ', ' and ') }}
                    on record — all of that will be permanently deleted along with their wallet.
                    @if (($wallet->balance ?? 0) > 0)
                        Their current balance of <strong>${{ number_format($wallet->balance, 2) }}</strong> will also be lost.
                    @endif
                    This cannot be undone.
                @else
                    No trades, investments, or other records reference this user, so this is safe to delete. This cannot be undone.
                @endif
            </flux:callout>

            <flux:input wire:model.live="deleteConfirmation" label="Type DELETE to confirm" placeholder="DELETE" />

            <div class="flex gap-3 justify-end">
                <flux:button variant="outline" wire:click="closeDeleteModal">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteUser" wire:loading.attr="disabled" wire:target="deleteUser" :disabled="$deleteConfirmation !== 'DELETE'">
                    Delete Permanently
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
