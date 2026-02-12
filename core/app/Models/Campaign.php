<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'discount_text',
        'discount_code',
        'start_date',
        'end_date',
        'is_active',
        'button_text',
        'button_link'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
