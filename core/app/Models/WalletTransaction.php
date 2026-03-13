<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'balance_after',
        'type',
        'status',
        'description',
        'reference_id',
        'meta_data',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
