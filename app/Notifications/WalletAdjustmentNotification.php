<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletAdjustmentNotification extends Notification
{
    public function __construct(protected WalletTransaction $transaction, protected string $categoryLabel)
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
        $isCredit = $this->transaction->amount >= 0;
        $amount = number_format(abs((float) $this->transaction->amount), 2);

        return [
            'title' => ($isCredit ? 'Balance credited' : 'Balance debited')." — {$this->categoryLabel}",
            'message' => ($isCredit ? "\${$amount} was added to" : "\${$amount} was deducted from")." your account ({$this->categoryLabel}).",
        ];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $isCredit = $this->transaction->amount >= 0;
        $amount = number_format(abs((float) $this->transaction->amount), 2);

        $mail = (new MailMessage)
            ->subject($isCredit ? 'Your Account Was Credited' : 'Your Account Was Debited')
            ->greeting('Hi '.$notifiable->name.',')
            ->line(($isCredit ? "\${$amount} was added to" : "\${$amount} was deducted from")." your account under **{$this->categoryLabel}**.");

        if ($this->transaction->note) {
            $mail->line('Note: '.$this->transaction->note);
        }

        return $mail->action('View Transactions', route('transactions'));
    }
}
