<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'wedding_event_id',
        'image_url',
        'thumbnail_url',
        'description',
        'sort_order',
        'is_background',
    ];

    /**
     * Get the wedding event that owns the gallery image.
     */
    public function weddingEvent(): BelongsTo
    {
        return $this->belongsTo(WeddingEvent::class);
    }
}
