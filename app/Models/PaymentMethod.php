<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'payment_method_name',
        'description',
        'provider_admin_fee',
        'provider_merchant_fee',
        'admin_fee',
        'merchant_fee',
        'm_key',
    ];

    protected $casts = [
        'provider_admin_fee' => 'decimal:2',
        'provider_merchant_fee' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'merchant_fee' => 'decimal:2',
    ];

    /**
     * Get the payment transactions for the payment method.
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
