<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycStatusNotification extends Notification
{
    public function __construct(protected bool $approved, protected ?string $rejectionReason = null)
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
        return $this->approved
            ? [
                'title' => 'KYC approved',
                'message' => 'Your identity verification has been approved.',
            ]
            : [
                'title' => 'KYC rejected',
                'message' => 'Your identity verification was rejected. '.$this->rejectionReason,
            ];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $mail = (new MailMessage)->greeting('Hi '.$notifiable->name.',');

        if ($this->approved) {
            return $mail->subject('KYC Verification Approved')
                ->line('Your identity verification has been approved. Your account now has full access.')
                ->action('Go to Dashboard', route('dashboard'));
        }

        $mail->subject('KYC Verification Rejected')
            ->line('Your identity verification was rejected.');

        if ($this->rejectionReason) {
            $mail->line('Reason: '.$this->rejectionReason);
        }

        return $mail->line('Please resubmit your documents to try again.')
            ->action('Resubmit KYC', route('profile.edit'));
    }
}
