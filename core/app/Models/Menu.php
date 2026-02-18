<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use \App\Traits\LogsActivity, HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'business_id',
        'category', // Starter, Main, Dessert, Drink, etc.
        'name',
        'description',
        'price',
        'options',
        'unit_type',
        'image',
        'is_available',
        'background_color',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'calories',
        'stock_enabled',
        'stock_quantity',
        'low_stock_alert',
    ];

    protected $casts = [
        'options' => 'array',
        'is_available' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean',
        'stock_enabled' => 'boolean',
        'stock_quantity' => 'decimal:3',
        'low_stock_alert' => 'decimal:3',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }
        // If it's already a full URL, return it
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Return storage URL
        return asset('storage/'.$this->image);
    }
}
