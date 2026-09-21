<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketReplyNotification extends Notification
{
    public function __construct(protected SupportTicket $ticket)
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
        return [
            'title' => 'Support replied: '.$this->ticket->subject,
            'message' => $this->ticket->admin_response,
        ];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Re: '.$this->ticket->subject)
            ->greeting('Hi '.$notifiable->name.',')
            ->line('Our support team replied to your ticket "'.$this->ticket->subject.'":')
            ->line($this->ticket->admin_response)
            ->action('View Ticket', route('support'));
    }
}
