<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waitlist extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'reservation_date',
        'preferred_time_range',
        'guest_count',
        'status',
        'note',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    /**
     * Get the user that is waiting.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business the user is waiting for.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
