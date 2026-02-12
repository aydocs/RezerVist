<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'resource_id',
        'reservation_id',
        'waiter_id',
        'status',
        'total_amount',
        'paid_amount',
        'payment_status',
        'payment_method',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
