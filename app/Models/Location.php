<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $fillable = [
        'wedding_event_id',
        'venue_name',
        'address',
        'map_embed_url',
    ];

    /**
     * Get the wedding event that owns the location.
     */
    public function weddingEvent(): BelongsTo
    {
        return $this->belongsTo(WeddingEvent::class);
    }
}
