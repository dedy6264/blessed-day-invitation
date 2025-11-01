<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageSegment extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'price',
        'status',
        'period',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'period' => 'integer',
    ];

    /**
     * Get the package that owns the segment.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
