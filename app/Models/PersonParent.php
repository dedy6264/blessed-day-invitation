<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonParent extends Model
{
    protected $fillable = [
        'person_id',
        'father_name',
        'father_status',
        'mother_name',
        'mother_status',
    ];

    /**
     * Get the person that owns the parent information.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
