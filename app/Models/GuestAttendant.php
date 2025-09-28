<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestAttendant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'guest_id',
        'wedding_event_id',
        'guest_name',
        'checked_in_at'
    ];
    
    protected $casts = [
        'checked_in_at' => 'datetime',
    ];
    
    /**
     * Get the guest that attended.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
    
    /**
     * Get the wedding event for this attendance.
     */
    public function weddingEvent(): BelongsTo
    {
        return $this->belongsTo(WeddingEvent::class);
    }
}
