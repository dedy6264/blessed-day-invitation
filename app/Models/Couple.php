<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Couple extends Model
{
    protected $fillable = [
        'client_id',
        'groom_name',
        'bride_name',
        'wedding_date',
    ];

    protected $casts = [
        'wedding_date' => 'date',
    ];

    /**
     * Get the client that owns the couple.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the persons for the couple.
     */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    /**
     * Get the wedding events for the couple.
     */
    public function weddingEvents(): HasMany
    {
        return $this->hasMany(WeddingEvent::class);
    }

    /**
     * Get the timeline events for the couple.
     */
    public function timelineEvents(): HasMany
    {
        return $this->hasMany(TimelineEvent::class);
    }

    /**
     * Get the transactions for the couple.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    public function lastTransaction()
    {
        return $this->hasOne(Transaction::class)->latest(); 
    }
    
}
