<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $fillable = [
        'couple_id',
        'name',
        'email',
        'phone',
        'guest_index',
    ];

    /**
     * Get the couple that owns the guest.
     */
    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Get the invitations for the guest.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the guest messages for the guest.
     */
    public function guestMessages(): HasMany
    {
        return $this->hasMany(GuestMessage::class);
    }
}
