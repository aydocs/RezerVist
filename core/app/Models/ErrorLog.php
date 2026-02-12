<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'message',
        'level',
        'exception',
        'file',
        'line',
        'trace',
        'url',
        'method',
        'ip',
        'user_agent',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with the error
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for filtering by level
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope for critical errors
     */
    public function scopeCritical($query)
    {
        return $query->whereIn('level', ['critical', 'emergency']);
    }

    /**
     * Scope for recent errors
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}
