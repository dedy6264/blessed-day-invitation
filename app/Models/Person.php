<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    protected $fillable = [
        'person_index',
        'couple_id',
        'role',
        'full_name',
        'image_url',
        'additional_info',
    ];

    protected static function booted()
    {
        static::creating(function ($person) {
            // Generate person_index before creating
            if (empty($person->person_index)) {
                $person->person_index = $person->generatePersonIndex();
            }
        });
        
        static::updating(function ($person) {
            // Regenerate person_index if couple_id or role changes
            if ($person->isDirty(['couple_id', 'role']) && !empty($person->couple_id) && !empty($person->role)) {
                $person->person_index = $person->generatePersonIndex();
            }
        });
    }

    /**
     * Generate person_index by combining client ID and role
     */
    public function generatePersonIndex(): string
    {
        if (!empty($this->couple_id) && !empty($this->role)) {
            // Load the couple with client if not already loaded
            if (!$this->relationLoaded('couple')) {
                $this->load('couple');
            }
            
            // Get client ID from couple
            $clientId = $this->couple ? $this->couple->client_id : null;
            
            if ($clientId) {
                return $clientId . $this->role;
            }
        }
        
        // Fallback to a temporary value if we can't generate it yet
        return uniqid('temp_', true);
    }

    /**
     * Get the couple that owns the person.
     */
    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Get the person parent for the person.
     */
    public function personParent(): HasOne
    {
        return $this->hasOne(PersonParent::class);
    }
}
