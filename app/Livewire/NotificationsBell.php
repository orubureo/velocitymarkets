<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsBell extends Component
{
    public string $variant = 'expanded';

    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.notifications-bell', [
            'notifications' => $user->notifications()->latest()->take(8)->get(),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
