<div class="flex flex-col gap-8 stagger-children">

    {{-- Page Header --}}
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">{{ __('Identity Verification') }}</flux:heading>
        <flux:text class="text-zinc-500">{{ __('Verify your identity with a government-issued ID and a selfie.') }}</flux:text>
    </div>

    <flux:card class="trading-card flex flex-col gap-6 max-w-2xl">
        @if ($user->kyc_status === 'approved')
            <div class="flex items-start gap-3 p-4 rounded-xl bg-green-500/5 border border-green-500/10">
                <div class="stat-icon-up !rounded-full shrink-0">
                    <flux:icon name="check-badge" class="size-5" />
                </div>
                <div>
                    <flux:heading size="sm">{{ __('You\'re verified') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">
                        {{ __('Your identity was verified on :date.', ['date' => $user->kyc_reviewed_at?->format('M j, Y') ?? '—']) }}
                    </flux:text>
                </div>
            </div>
        @elseif ($user->kyc_status === 'pending')
            <div class="flex items-start gap-3 p-4 rounded-xl bg-sky-500/5 border border-sky-500/10">
                <div class="stat-icon-sky !rounded-full shrink-0">
                    <flux:icon name="clock" class="size-5" />
                </div>
                <div>
                    <flux:heading size="sm">{{ __('Under review') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">
                        {{ __('You submitted your documents on :date. Reviews are usually completed within a day.', ['date' => $user->kyc_submitted_at?->format('M j, Y') ?? '—']) }}
                    </flux:text>
                </div>
            </div>
        @else
            @if ($user->kyc_status === 'rejected')
                <flux:callout variant="danger" icon="exclamation-triangle">
                    <flux:callout.heading>{{ __('Verification rejected') }}</flux:callout.heading>
                    <flux:callout.text>{{ $user->kyc_rejection_reason }}</flux:callout.text>
                </flux:callout>
            @endif

            <form wire:submit="submit" class="space-y-6">
                <flux:select wire:model="documentType" :label="__('Document type')">
                    <flux:select.option value="passport">{{ __('Passport') }}</flux:select.option>
                    <flux:select.option value="national_id">{{ __('National ID') }}</flux:select.option>
                    <flux:select.option value="drivers_license">{{ __('Driver\'s license') }}</flux:select.option>
                </flux:select>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <flux:text class="font-medium text-zinc-900 dark:text-white mb-2 block">{{ __('ID document') }}</flux:text>
                        <div class="flex flex-col gap-2">
                            @if ($documentFile)
                                <img src="{{ $documentFile->temporaryUrl() }}" class="aspect-video w-full rounded-lg object-cover ring-2 ring-zinc-200 dark:ring-zinc-700" alt="{{ __('ID document preview') }}">
                            @else
                                <div class="aspect-video w-full rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 ring-2 ring-zinc-200 dark:ring-zinc-700">
                                    <flux:icon name="identification" class="size-8" />
                                </div>
                            @endif

                            <label class="cursor-pointer">
                                <span class="inline-flex items-center justify-center gap-1.5 w-full px-3 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                    <flux:icon name="camera" class="size-4" />
                                    {{ __('Choose Photo') }}
                                </span>
                                <input type="file" wire:model="documentFile" accept="image/png,image/jpeg" class="hidden">
                            </label>
                            <div wire:loading wire:target="documentFile" class="text-xs text-zinc-500">{{ __('Uploading…') }}</div>
                            @error('documentFile')
                                <flux:text size="sm" class="text-red-500">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <flux:text class="font-medium text-zinc-900 dark:text-white mb-2 block">{{ __('Selfie') }}</flux:text>
                        <div class="flex flex-col gap-2">
                            @if ($selfieFile)
                                <img src="{{ $selfieFile->temporaryUrl() }}" class="aspect-video w-full rounded-lg object-cover ring-2 ring-zinc-200 dark:ring-zinc-700" alt="{{ __('Selfie preview') }}">
                            @else
                                <div class="aspect-video w-full rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 ring-2 ring-zinc-200 dark:ring-zinc-700">
                                    <flux:icon name="face-smile" class="size-8" />
                                </div>
                            @endif

                            <label class="cursor-pointer">
                                <span class="inline-flex items-center justify-center gap-1.5 w-full px-3 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                    <flux:icon name="camera" class="size-4" />
                                    {{ __('Choose Photo') }}
                                </span>
                                <input type="file" wire:model="selfieFile" accept="image/png,image/jpeg" class="hidden">
                            </label>
                            <div wire:loading wire:target="selfieFile" class="text-xs text-zinc-500">{{ __('Uploading…') }}</div>
                            @error('selfieFile')
                                <flux:text size="sm" class="text-red-500">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>
                </div>

                <flux:text size="xs" class="text-zinc-500">{{ __('JPG or PNG. Max 10MB each. Make sure all details are clearly legible.') }}</flux:text>

                <div class="flex items-center gap-4 justify-end">
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:target="submit">
                        {{ __('Submit for Review') }}
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:card>
</div>
