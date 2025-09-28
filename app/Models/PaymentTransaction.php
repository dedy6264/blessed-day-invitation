<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'payment_method_id',
        'payment_method_name',
        'provider_admin_fee',
        'provider_merchant_fee',
        'admin_fee',
        'merchant_fee',
        'status_code',
        'status_message',
        'payment_other_reff',
    ];

    protected $casts = [
        'provider_admin_fee' => 'decimal:2',
        'provider_merchant_fee' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'merchant_fee' => 'decimal:2',
    ];

    /**
     * Get the transaction that owns the payment transaction.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the payment method for the payment transaction.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
