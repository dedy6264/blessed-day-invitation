<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invitation extends Model
{
    protected $fillable = [
        'guest_id',
        'wedding_event_id',
        'invitation_code',
        'is_attending',
        'responded_at',
        'guest_count',
    ];

    protected $casts = [
        'is_attending' => 'boolean',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the guest that owns the invitation.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the wedding event for the invitation.
     */
    public function weddingEvent(): BelongsTo
    {
        return $this->belongsTo(WeddingEvent::class);
    }

    /**
     * Get the QR code for the invitation.
     */
    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class);
    }
}
