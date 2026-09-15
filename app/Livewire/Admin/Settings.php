<?php

namespace App\Livewire\Admin;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Admin Settings')]
#[Layout('layouts.admin')]
class Settings extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public $avatar = null;

    public function mount(): void
    {
        $admin = Auth::guard('admin')->user();

        $this->name = $admin->name;
        $this->email = $admin->email;
    }

    public function updateProfile(): void
    {
        $admin = Auth::guard('admin')->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
        ]);

        $admin->fill($validated)->save();

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    public function updateAvatar(): void
    {
        $this->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $admin = Auth::guard('admin')->user();

        if ($admin->avatar_path) {
            Storage::disk('public')->delete($admin->avatar_path);
        }

        $admin->forceFill([
            'avatar_path' => $this->avatar->store('avatars', 'public'),
        ])->save();

        $this->reset('avatar');

        Flux::toast(variant: 'success', text: __('Profile photo updated.'));
    }

    public function removeAvatar(): void
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->avatar_path) {
            Storage::disk('public')->delete($admin->avatar_path);
            $admin->forceFill(['avatar_path' => null])->save();
        }

        Flux::toast(variant: 'success', text: __('Profile photo removed.'));
    }

    public function updatePassword(): void
    {
        $admin = Auth::guard('admin')->user();

        $validated = $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('The provided password does not match your current password.'),
            ]);
        }

        $admin->forceFill(['password' => Hash::make($validated['password'])])->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        Flux::toast(variant: 'success', text: __('Password updated.'));
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
