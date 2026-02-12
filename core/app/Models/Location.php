<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'name',
        'address',
        'city',
        'district',
        'phone',
        'email',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
