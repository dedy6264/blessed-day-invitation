<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'client_name',
        'address',
        'nik',
        'phone',
    ];

    /**
     * Get the couples for the client.
     */
    public function couples(): HasMany
    {
        return $this->hasMany(Couple::class);
    }
}
