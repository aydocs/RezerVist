<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes, \App\Traits\LogsActivity;

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
        'calories'
    ];

    protected $casts = [
        'options' => 'array',
        'is_available' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        // If it's already a full URL, return it
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        // Return storage URL
        return asset('storage/' . $this->image);
    }
}
