<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to VelocityMarkets')
            ->greeting('Welcome, '.$notifiable->name.'!')
            ->line('Your VelocityMarkets account is ready. You can now deposit funds, explore markets and start trading.')
            ->action('Go to Dashboard', route('dashboard'))
            ->line('If you didn\'t create this account, please contact support immediately.');
    }
}
