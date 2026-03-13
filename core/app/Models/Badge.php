<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'points_required',
        'criteria',
    ];

    protected $casts = [
        'criteria' => 'array',
        'points_required' => 'integer',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }
}
