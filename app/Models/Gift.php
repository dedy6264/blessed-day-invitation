<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gift extends Model
{
    protected $fillable = [
        'wedding_event_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'gift_type',
        'gift_description',
    ];

    /**
     * Get the wedding event that owns the gift.
     */
    public function weddingEvent(): BelongsTo
    {
        return $this->belongsTo(WeddingEvent::class);
    }
}
