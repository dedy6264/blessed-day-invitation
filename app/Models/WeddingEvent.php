<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WeddingEvent extends Model
{
    protected $fillable = [
        'couple_id',
        'event_name',
        'event_date',
        'event_time',
        'end_time',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    /**
     * Get the couple that owns the wedding event.
     */
    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Get the location for the wedding event.
     */
    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    /**
     * Get the gallery images for the wedding event.
     */
    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    /**
     * Get the gifts for the wedding event.
     */
    public function gifts(): HasMany
    {
        return $this->hasMany(Gift::class);
    }

    /**
     * Get the guest messages for the wedding event.
     */
    public function guestMessages(): HasMany
    {
        return $this->hasMany(GuestMessage::class);
    }

    /**
     * Get the invitations for the wedding event.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}
