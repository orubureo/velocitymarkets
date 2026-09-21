<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChangedNotification extends Notification
{
    public function __construct(protected bool $blocked)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * @return array{title: string, message: string}
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->blocked
            ? [
                'title' => 'Account blocked',
                'message' => 'Your account has been blocked. Contact support for assistance.',
            ]
            : [
                'title' => 'Account unblocked',
                'message' => 'Your account has been restored. You can log in again.',
            ];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $mail = (new MailMessage)->greeting('Hi '.$notifiable->name.',');

        return $this->blocked
            ? $mail->subject('Your Account Has Been Blocked')
                ->line('Your VelocityMarkets account has been blocked and you will not be able to log in.')
                ->line('If you believe this is a mistake, please contact support.')
            : $mail->subject('Your Account Has Been Restored')
                ->line('Your VelocityMarkets account has been unblocked. You can log in again.')
                ->action('Log In', route('login'));
    }
}
