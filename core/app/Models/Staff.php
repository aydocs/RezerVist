<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\HasBusinessScope;

    protected $fillable = [
        'business_id',
        'location_id',
        'name',
        'email',
        'phone',
        'position',
        'pin_code',
        'permissions',
        'photo_path',
        'is_active',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function updateRating()
    {
        $avg = $this->reviews()->avg('rating');
        if ($avg) {
            $this->update([
                'rating' => round($avg, 1),
                'rating_count' => $this->reviews()->count(),
            ]);
        }
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function schedules()
    {
        return $this->hasMany(StaffSchedule::class);
    }
}
