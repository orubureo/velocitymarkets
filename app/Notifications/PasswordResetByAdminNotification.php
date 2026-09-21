<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetByAdminNotification extends Notification
{
    public function __construct(protected string $newPassword)
    {
        //
    }

    /**
     * Mail only — the plaintext password must never be persisted, so this
     * intentionally skips the 'database' channel used elsewhere.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Password Was Reset')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('An administrator has reset your VelocityMarkets password. Your new temporary password is:')
            ->line('**'.$this->newPassword.'**')
            ->line('Please log in and change it right away from your account settings.')
            ->action('Log In', route('login'))
            ->line('If you didn\'t expect this, contact support immediately.');
    }
}
