<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManySegments;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the transactions for the package.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the segments for the package.
     */
    public function segments(): HasManySegments
    {
        return $this->hasMany(PackageSegment::class);
    }
}
