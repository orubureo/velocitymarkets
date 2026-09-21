<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletTransactionUpdated extends Notification
{
    public function __construct(protected WalletTransaction $transaction)
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

    public function toMail(User $notifiable): MailMessage
    {
        $type = $this->transaction->type === 'withdrawal' ? 'Withdrawal' : 'Deposit';
        $approved = $this->transaction->status === 'approved';
        $amount = number_format(abs((float) $this->transaction->amount), 2);

        $mail = (new MailMessage)
            ->subject("{$type} ".($approved ? 'Approved' : 'Rejected'))
            ->greeting('Hi '.$notifiable->name.',');

        return $approved
            ? $mail->line("Your {$type} of \${$amount} has been approved and reflected in your balance.")
                ->action('View Transactions', route('transactions'))
            : $mail->line("Your {$type} of \${$amount} was rejected. Contact support if you have questions.")
                ->action('Contact Support', route('support'));
    }

    /**
     * @return array{title: string, message: string, status: string, transaction_type: string, transaction_id: int}
     */
    public function toDatabase(object $notifiable): array
    {
        $type = $this->transaction->type === 'withdrawal' ? 'Withdrawal' : 'Deposit';
        $approved = $this->transaction->status === 'approved';
        $amount = number_format(abs((float) $this->transaction->amount), 2);

        return [
            'title' => "{$type} ".($approved ? 'approved' : 'rejected'),
            'message' => $approved
                ? "Your {$type} of \${$amount} has been approved and reflected in your balance."
                : "Your {$type} of \${$amount} was rejected. Contact support if you have questions.",
            'status' => $this->transaction->status,
            'transaction_type' => $this->transaction->type,
            'transaction_id' => $this->transaction->id,
        ];
    }
}
