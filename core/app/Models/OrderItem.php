<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'name',
        'quantity',
        'selected_options',
        'weight_grams',
        'unit_price',
        'total_price',
        'notes',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'decimal:2', // Change to decimal for fractional weights
        'selected_options' => 'array',
        'weight_grams' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
