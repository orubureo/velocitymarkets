<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsBell extends Component
{
    public function markAllRead(): void
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();

        return view('livewire.admin.notifications-bell', [
            'notifications' => $admin->notifications()->latest()->take(6)->get(),
            'unreadCount' => $admin->unreadNotifications()->count(),
        ]);
    }
}
