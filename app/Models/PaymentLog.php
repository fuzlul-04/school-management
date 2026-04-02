<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    protected $fillable = [
        'payment_id', 'gateway', 'transaction_id', 'status_code',
        'status_message', 'request_payload', 'response_payload',
        'error_message', 'is_success',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'is_success' => 'boolean',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}