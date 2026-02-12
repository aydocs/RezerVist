<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'reservation_id',
        'payment_id',
        'conversation_id',
        'price',
        'paid_price',
        'status',
        'currency',
        'basket_id',
        'card_family',
        'card_type',
        'bin_number',
        'raw_result',
    ];

    protected $casts = [
        'raw_result' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
