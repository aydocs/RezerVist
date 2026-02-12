<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessImage extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'image_path', 'thumbnail_path'];

    // Accessor to get full URL
    public function getUrlAttribute()
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }

    // Accessor to get thumbnail URL
    public function getThumbUrlAttribute()
    {
        $path = $this->thumbnail_path ?? $this->image_path;
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return asset('storage/' . $path);
    }
}
