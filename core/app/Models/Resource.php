<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory, \App\Traits\HasBusinessScope;

    protected $fillable = [
        'business_id',
        'location_id',
        'name',
        'capacity',
        'type',
        'stock',
        'requires_inventory',
        'is_available',
        'is_reservation_enabled',
        'category',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_reservation_enabled' => 'boolean',
        'requires_inventory' => 'boolean',
        'stock' => 'integer',
        'rating' => 'decimal:2',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

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

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
