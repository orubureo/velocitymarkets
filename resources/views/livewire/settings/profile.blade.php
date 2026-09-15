<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-settings.layout :heading="__('Basic information')" :subheading="__('Update your personal details and contact information.')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="name" :label="__('Full name')" type="text" required autofocus autocomplete="name" />
                <flux:input wire:model="phone" :label="__('Phone')" type="text" required />

                <div>
                    <flux:input wire:model="email" :label="__('Email address')" type="email" required autocomplete="email" />

                    @if ($this->hasUnverifiedEmail)
                        <div>
                            <flux:text class="mt-4">
                                {{ __('Your email address is unverified.') }}

                                <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </flux:link>
                            </flux:text>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <flux:select wire:model="country" :label="__('Country')" placeholder="Select your country">
                        @foreach(config('countries') as $code => $label)
                            <flux:select.option value="{{ $label }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <div class="flex items-center gap-4 justify-end">
                <flux:button variant="primary" type="submit">{{ __('Save changes') }}</flux:button>
            </div>
        </form>

        <flux:separator class="my-8" />

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>
