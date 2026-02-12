<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'verified',
        'type',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    /**
     * Check if verification code is expired
     */
    public function isExpired()
    {
        return now()->gt($this->expires_at);
    }

    /**
     * Generate a random 6-digit code
     */
    public static function generateCode()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create or update verification code for a phone
     */
    public static function createForPhone($phone, $type = 'registration')
    {
        // Delete old unverified codes
        self::where('phone', $phone)
            ->where('verified', false)
            ->delete();

        return self::create([
            'phone' => $phone,
            'code' => self::generateCode(),
            'expires_at' => now()->addMinutes(10),
            'type' => $type,
        ]);
    }
}
