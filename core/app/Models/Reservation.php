<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use \App\Traits\LogsActivity, HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_id',
        'location_id',
        'resource_id',
        'staff_id',
        'start_time',
        'end_time',
        'status',
        'note',
        'price',
        'total_amount',
        'guest_count',
        'reminded_at',
        'coupon_id',
        'discount_amount',
        'points_spent',
        'loyalty_discount',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_end_date',
        'parent_reservation_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'recurrence_end_date' => 'datetime',
        'is_recurring' => 'boolean',
        'points_spent' => 'integer',
        'loyalty_discount' => 'decimal:2',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function parentReservation()
    {
        return $this->belongsTo(Reservation::class, 'parent_reservation_id');
    }

    public function recurringInstances()
    {
        return $this->hasMany(Reservation::class, 'parent_reservation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withPivot(['quantity', 'price'])->withTimestamps();
    }
}
