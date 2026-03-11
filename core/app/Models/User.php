<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use \App\Traits\LogsActivity, HasApiTokens, HasFactory, HasPushSubscriptions, \Illuminate\Database\Eloquent\SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'plain_password', // REMOVED: Security Risk
        'phone',
        'address',
        'city',
        'district',
        'country',
        'zip_code',
        'google_id',
        'apple_id',
        'avatar',
        'business_id',
        'profile_photo_path',
        'role',
        'balance',
        'points',
        'last_reservation_at',
        'is_review_blocked',
        'referral_code',
        'referred_by_id',
        'email_verified_at',
        'profile_photo_blob',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'initials',
    ];

    public function rewardPoints($amount)
    {
        $this->increment('points', $amount);
        $this->update(['last_reservation_at' => now()]);
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = static::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
    ];

    public function ownedBusiness()
    {
        return $this->hasOne(Business::class, 'owner_id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'owner_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function businessApplications()
    {
        return $this->hasMany(BusinessApplication::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        // Prioritize blob photo
        if ($this->profile_photo_blob) {
            return route('api.pos.image', ['id' => $this->id, 'type' => 'user', 't' => $this->updated_at?->timestamp]);
        }

        // Prioritize uploaded photo, then social avatar, then default
        if ($this->profile_photo_path) {
            return asset('storage/'.$this->profile_photo_path);
        }

        if ($this->avatar) {
            return $this->avatar;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= mb_substr($name, 0, 1);
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }

        return mb_strtoupper($initials);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function getUnreadMessagesCountAttribute()
    {
        return $this->messages()->where('is_read', false)->count();
    }

    public function isProfileComplete(): bool
    {
        return ! empty($this->phone); // Only require phone now
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getReviewStatsAttribute()
    {
        return [
            'total' => $this->reviews()->withTrashed()->count(),
            'deleted' => $this->reviews()->onlyTrashed()->count(),
            'is_blocked' => $this->is_review_blocked,
        ];
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }
}
