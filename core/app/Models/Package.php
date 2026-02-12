<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price_monthly',
        'price_yearly',
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Helper to check feature access
    public function hasFeature($key)
    {
        return $this->features[$key] ?? false; // Default false if key doesn't exist
    }
}
