<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{

    protected $fillable = [
        'title', 'slug', 'content', 'featured_image', 
        'category_id', 'author_id', 'meta_title', 
        'meta_description', 'is_published', 'published_at',
        'views', 'is_featured', 'tags'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }
}
