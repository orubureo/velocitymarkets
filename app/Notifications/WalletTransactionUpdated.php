<?php

namespace App\Notifications;

use App\Models\WalletTransaction;
use Illuminate\Notifications\Notification;

class WalletTransactionUpdated extends Notification
{
    public function __construct(protected WalletTransaction $transaction)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

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
