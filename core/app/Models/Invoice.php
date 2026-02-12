<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'business_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'currency',
        'status',
        'paid_at',
        'payment_method',
        'billing_name',
        'billing_address',
        'tax_office',
        'tax_number',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
