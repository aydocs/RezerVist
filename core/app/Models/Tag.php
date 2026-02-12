<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Added for the relationship

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'icon'];

    public function businesses()
    {
        return $this->belongsToMany(Business::class);
    }
}
