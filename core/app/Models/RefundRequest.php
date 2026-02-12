<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'reservation_id',
        'user_id',
        'amount',
        'status',
        'iyzico_payment_id',
        'iyzico_conversation_id',
        'admin_note',
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
