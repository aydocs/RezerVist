<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, \App\Traits\Cacheable, \App\Traits\LogsActivity;



    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
    ];

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
