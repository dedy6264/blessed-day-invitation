<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'couple_id',
        'package_id',
        'order_date',
        'status',
        'total_amount',
        'paid_at',
        'expired_at',
        'reference_no',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Generate a unique reference number for the transaction.
     */
    public static function generateReferenceNo(): string
    {
        $prefix = 'INV'; // INV for invoice
        $timestamp = now()->format('ymd'); // YYMMDD format
        $random = Str::upper(Str::random(6)); // 6 random characters
        
        $referenceNo = $prefix . $timestamp . $random;
        
        // Check if the reference number already exists
        while (self::where('reference_no', $referenceNo)->exists()) {
            $random = Str::upper(Str::random(6));
            $referenceNo = $prefix . $timestamp . $random;
        }
        
        return $referenceNo;
    }

    /**
     * Get the couple that owns the transaction.
     */
    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Get the package for the transaction.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the payment transactions for the transaction.
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
