<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
          'price',
        'period',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'period' => 'integer',
    ];

    /**
     * Get the transactions for the package.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

}
