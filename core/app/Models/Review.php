<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, \App\Traits\LogsActivity, \Illuminate\Database\Eloquent\SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = ['user_id', 'business_id', 'staff_id', 'resource_id', 'rating', 'comment', 'status', 'is_reported', 'reported_at'];

    protected static function booted()
    {
        static::saved(function ($review) {
            // Update ratings whenever a review is saved (status might have changed)
            $review->business->updateRating();
            if ($review->staff) $review->staff->updateRating();
            if ($review->resource) $review->resource->updateRating();
        });

        static::deleted(function ($review) {
            $review->business->updateRating();
            if ($review->staff) $review->staff->updateRating();
            if ($review->resource) $review->resource->updateRating();
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
