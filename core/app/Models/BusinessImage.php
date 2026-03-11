<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessImage extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'image_path', 'thumbnail_path', 'image_blob'];

    // Accessor to get full URL
    public function getUrlAttribute()
    {
        if ($this->image_blob) {
            return route('api.pos.image', ['id' => $this->id, 'type' => 'business_image', 't' => $this->updated_at?->timestamp]);
        }

        if (str_starts_with($this->image_path ?? '', 'http')) {
            return $this->image_path;
        }

        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    // Accessor to get thumbnail URL
    public function getThumbUrlAttribute()
    {
        if ($this->image_blob) {
            return route('api.pos.image', ['id' => $this->id, 'type' => 'business_image', 'thumb' => 1, 't' => $this->updated_at?->timestamp]);
        }

        $path = $this->thumbnail_path ?? $this->image_path;
        if (str_starts_with($path ?? '', 'http')) {
            return $path;
        }

        return $path ? asset('storage/'.$path) : null;
    }
}
