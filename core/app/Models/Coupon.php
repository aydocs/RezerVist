<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'code',
        'type', // fixed, percentage
        'value',
        'min_amount',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function isValid(?float $amount = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($amount && $amount < $this->min_amount) return false;

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }

        return min($this->value, $amount);
    }
}
