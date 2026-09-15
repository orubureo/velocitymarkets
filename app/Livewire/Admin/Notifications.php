<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Notifications')]
#[Layout('layouts.admin')]
class Notifications extends Component
{
    use WithPagination;

    public function markAllRead(): void
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();
    }

    public function markRead(string $notificationId): void
    {
        Auth::guard('admin')->user()->notifications()
            ->where('id', $notificationId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();

        return view('livewire.admin.notifications', [
            'notifications' => $admin->notifications()->latest()->paginate(20),
            'unreadCount' => $admin->unreadNotifications()->count(),
        ]);
    }
}
