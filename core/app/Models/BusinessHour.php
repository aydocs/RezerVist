<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
        'special_date',
        'note',
        'discount_percent',
    ];

    protected $casts = [
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'special_date' => 'date',
        'is_closed' => 'boolean',
        'discount_percent' => 'integer',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get day name in Turkish
     */
    public function getDayNameAttribute()
    {
        $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
        return $days[$this->day_of_week] ?? '';
    }

    /**
     * Check if business is open at given time
     */
    public static function isOpenAt($businessId, $datetime)
    {
        $dayOfWeek = $datetime->dayOfWeek;
        $time = $datetime->format('H:i');
        $date = $datetime->format('Y-m-d');

        // Check for special date first
        $specialHours = self::where('business_id', $businessId)
            ->where('special_date', $date)
            ->first();

        if ($specialHours) {
            if ($specialHours->is_closed) {
                return false;
            }
            $open = \Carbon\Carbon::parse($specialHours->open_time)->format('H:i');
            $close = \Carbon\Carbon::parse($specialHours->close_time)->format('H:i');
            return $time >= $open && $time <= $close;
        }

        // Check regular hours
        $regularHours = self::where('business_id', $businessId)
            ->where('day_of_week', $dayOfWeek)
            ->whereNull('special_date')
            ->first();

        // If no regular hours defined, we assume closed by default but we could check if ANY hours exist
        if (!$regularHours || $regularHours->is_closed) {
            return false;
        }

        $open = \Carbon\Carbon::parse($regularHours->open_time)->format('H:i');
        $close = \Carbon\Carbon::parse($regularHours->close_time)->format('H:i');
        
        return $time >= $open && $time <= $close;
    }
}
