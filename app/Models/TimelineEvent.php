<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEvent extends Model
{
    protected $fillable = [
        'couple_id',
        'title',
        'event_date',
        'description',
        'image_url',
        'sort_order',
        'is_inverted',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_inverted' => 'boolean',
    ];

    /**
     * Get the couple that owns the timeline event.
     */
    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }
}
