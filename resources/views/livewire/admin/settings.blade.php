<div class="flex flex-col gap-6">
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Admin Settings</flux:heading>
        <flux:text class="text-zinc-500">Manage your admin account profile and password.</flux:text>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="flex flex-col gap-6">
            <flux:card class="trading-card">
                <flux:heading size="lg" class="mb-1">Profile Photo</flux:heading>
                <flux:text size="sm" class="text-zinc-500 mb-4">JPG, PNG or WEBP. Max 2MB.</flux:text>

                <div class="flex items-center gap-5">
                    @if ($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="size-16 rounded-full object-cover ring-2 ring-accent/20" alt="Preview">
                    @else
                        <flux:avatar :src="Auth::guard('admin')->user()->avatarUrl()" :name="Auth::guard('admin')->user()->name"
                            :initials="Auth::guard('admin')->user()->initials()" size="xl" circle class="ring-2 ring-accent/20" />
                    @endif

                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="cursor-pointer">
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                    <flux:icon name="camera" class="size-4" />
                                    {{ __('Choose Photo') }}
                                </span>
                                <input type="file" wire:model="avatar" accept="image/png,image/jpeg,image/webp" class="hidden">
                            </label>

                            @if ($avatar)
                                <flux:button size="sm" variant="primary" wire:click="updateAvatar" wire:loading.attr="disabled">
                                    {{ __('Upload') }}
                                </flux:button>
                            @elseif (Auth::guard('admin')->user()->avatar_path)
                                <flux:button size="sm" variant="ghost" wire:click="removeAvatar">
                                    {{ __('Remove') }}
                                </flux:button>
                            @endif
                        </div>

                        <div wire:loading wire:target="avatar,updateAvatar" class="text-xs text-zinc-500">
                            {{ __('Uploading...') }}
                        </div>
                        @error('avatar')
                            <flux:text size="sm" class="text-red-500">{{ $message }}</flux:text>
                        @enderror
                    </div>
                </div>
            </flux:card>

            <flux:card class="trading-card">
                <flux:heading size="lg" class="mb-1">Profile Information</flux:heading>
                <flux:text size="sm" class="text-zinc-500 mb-4">Your name and email address.</flux:text>

                <form wire:submit="updateProfile" class="flex flex-col gap-4">
                    <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus />
                    <flux:input wire:model="email" :label="__('Email')" type="email" required />

                    <div>
                        <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
                    </div>
                </form>
            </flux:card>
        </div>

        <flux:card class="trading-card">
            <flux:heading size="lg" class="mb-1">Update Password</flux:heading>
            <flux:text size="sm" class="text-zinc-500 mb-4">Ensure your account uses a long, random password to stay secure.</flux:text>

            <form wire:submit="updatePassword" class="flex flex-col gap-4">
                <flux:input wire:model="current_password" :label="__('Current password')" type="password" required autocomplete="current-password" />
                <flux:input wire:model="password" :label="__('New password')" type="password" required autocomplete="new-password" />
                <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required autocomplete="new-password" />

                <div>
                    <flux:button type="submit" variant="primary">{{ __('Update Password') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
