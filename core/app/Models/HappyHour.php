<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HappyHour extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'type',
        'discount_value',
        'start_time',
        'end_time',
        'days_of_week',
        'applicable_categories',
        'applicable_items',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'applicable_categories' => 'array',
        'applicable_items' => 'array',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Check if this happy hour is currently active
     */
    public function isActiveNow(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();
        $currentDay = strtolower($now->format('l')); // 'monday', 'tuesday', etc.
        $currentTime = $now->format('H:i:s');

        // Check if today is in the applicable days
        if (! in_array($currentDay, $this->days_of_week)) {
            return false;
        }

        // Check if current time is within the range
        return $currentTime >= $this->start_time && $currentTime <= $this->end_time;
    }
}
