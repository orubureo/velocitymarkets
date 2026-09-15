<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    public const CATEGORIES = [
        'account' => 'Account & Verification',
        'deposits' => 'Deposits',
        'withdrawals' => 'Withdrawals',
        'trading' => 'Trading',
        'other' => 'Other',
    ];

    protected $fillable = [
        'user_id', 'subject', 'category', 'message', 'status', 'admin_response', 'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }
}
