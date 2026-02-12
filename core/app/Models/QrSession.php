<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'resource_id',
        'user_id',
        'order_id',
        'session_token',
        'status',
        'total_paid',
        'expires_at',
    ];

    protected $casts = [
        'total_paid' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a unique session token.
     */
    public static function generateToken(): string
    {
        return Str::random(48);
    }

    // ── Relationships ──

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    // ── Helpers ──

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function markCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }
}
