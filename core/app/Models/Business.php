<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use \App\Traits\Cacheable, \App\Traits\LogsActivity, HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * Clear home page cache when business is saved.
     */
    protected function getCacheKeysToClear(): array
    {
        return ['home_featured_businesses'];
    }

    protected static function booted()
    {
        static::saving(function ($business) {
            if (empty($business->slug) || $business->isDirty('name')) {
                $business->slug = \Illuminate\Support\Str::slug($business->name);

                // Ensure uniqueness
                $count = static::where('slug', 'like', $business->slug.'%')
                    ->where('id', '!=', $business->id)
                    ->count();

                if ($count > 0) {
                    $business->slug .= '-'.($count + 1);
                }
            }
        });

        static::created(function ($business) {
            // Automatically assign free plan when a business is created
            $package = Package::where('slug', 'free')->first();
            if ($package) {
                app(\App\Services\SubscriptionService::class)->assignPlan($business, $package, 120, 'system');
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Override resolveRouteBinding to support both slug and numeric ID fallback.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Try slug first
        $business = $this->where($field ?? 'slug', $value)->first();

        // If not found and value is numeric, try ID
        if (! $business && is_numeric($value)) {
            $business = $this->where('id', $value)->first();

            // Optional: Redirect to slug if ID was used (can be done in controller but here is cleaner for binding)
            if ($business) {
                // We'll let the controller handle redirect if we want, or just return it.
                // Returning it here allows the route to still work.
            }
        }

        return $business;
    }

    protected $fillable = [
        'owner_id',
        'category_id',
        'name',
        'slug',
        'description',
        'address',
        'latitude',
        'longitude',
        'phone',
        'rating',
        'is_verified',
        'verified_at',
        'logo',
        // 'commission_rate', // moved to fillable correctly
        'price_per_person',
        'min_reservation_amount',
        'is_active',
        'commission_rate',
        'surge_threshold',
        'surge_multiplier',
        'custom_domain',
        'waiter_kiosk_enabled',
        'kiosk_config',
        'occupancy_rate',
        'reservation_time_slots',
        'master_pin',
        'last_occupancy_update',
        'subscription_status',
        'subscription_ends_at',
        'device_fingerprint',
        'device_registered_at',
        'iyzico_submerchant_key',
        'submerchant_type',
        'legal_company_name',
        'tax_office',
        'tax_number',
        'iyzico_iban',
        'pricing_type',
        'menu_theme',
        'menu_color',
        'settings',
        'image_blob',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'waiter_kiosk_enabled' => 'boolean',
        'kiosk_config' => 'array',
        'reservation_time_slots' => 'array',
        'verified_at' => 'datetime',
        'last_occupancy_update' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'device_registered_at' => 'datetime',
        'settings' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    public function hours()
    {
        return $this->hasMany(BusinessHour::class);
    }

    public function hasHours()
    {
        return $this->hours()->count() > 0;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Check if a business is favorited by a user.
     */
    public function isFavoritedBy($userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function images()
    {
        return $this->hasMany(BusinessImage::class);
    }

    public function businessCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'business_tag', 'business_id', 'tag_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function getIsFavoriteAttribute()
    {
        if (! auth('sanctum')->check()) {
            return false;
        }

        return $this->favorites()->where('user_id', auth('sanctum')->id())->exists();
    }

    public function getIsOpenAttribute()
    {
        return BusinessHour::isOpenAt($this->id, now());
    }

    public function getStatusTextAttribute()
    {
        if (! $this->hasHours()) {
            return 'Çalışma Saatleri Belirtilmemiş';
        }

        return $this->is_open ? 'Açık' : 'Kapalı';
    }

    public function getCityAttribute()
    {
        return null;
    }

    protected $appends = ['is_favorite', 'is_open', 'status_text', 'city'];

    // ... (unchanged code) ...
    public function calculatePrice($date, $guests, $time = null)
    {
        $basePrice = $this->price_per_person > 0 ? $this->price_per_person : 0;

        // Pricing Logic: Fixed vs Per Person
        if ($this->pricing_type === 'per_person') {
            $total = $basePrice * $guests;
        } else {
            // Default: Fixed price (Reservation Fee) regardless of guest count
            $total = $basePrice;
        }

        // 1. Weekend Surcharge (20% increase)
        $dateObj = \Carbon\Carbon::parse($date);
        $dayOfWeek = $dateObj->dayOfWeek;
        if (in_array($dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY])) {
            $total *= 1.2;
        }

        // 2. Happy Hour Discount
        if ($time) {
            $hour = $this->hours()
                ->where('day_of_week', $dayOfWeek)
                ->where('is_closed', false)
                ->where('open_time', '<=', $time)
                ->where('close_time', '>=', $time)
                ->whereNotNull('discount_percent')
                ->first();

            if ($hour) {
                $total *= (1 - ($hour->discount_percent / 100));
            }
        }

        // 3. Surge Pricing (Dynamic Demand)
        if ($this->waiter_kiosk_enabled && $this->surge_threshold > 0) {
            $currentGuests = $this->reservations()
                ->whereDate('start_time', $date)
                ->whereTime('start_time', $time)
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->sum('guest_count');

            $totalCapacity = $this->resources()->sum('capacity');

            if ($totalCapacity > 0) {
                $occupancyRate = ($currentGuests / $totalCapacity) * 100;

                if ($occupancyRate >= $this->surge_threshold) {
                    $total *= ($this->surge_multiplier ?: 1.5);
                }
            }
        }

        return round($total, 2);
    }

    /**
     * Convert loyalty points to TL discount.
     * 100 Points = 10 TL
     */
    public function pointsToCurrency($points)
    {
        return round($points / 10, 2);
    }

    /**
     * Update the business rating based on its reviews.
     * Rounds to single decimal or 0.5 nearest.
     */
    public function updateRating()
    {
        $average = $this->reviews()->approved()->avg('rating');

        // Round to nearest 0.5 (e.g., 4.2 -> 4.0, 4.3 -> 4.5, 4.7 -> 4.5, 4.8 -> 5.0)
        $rounded = $average ? round($average * 2) / 2 : 0;

        $this->update(['rating' => $rounded]);
    }

    /**
     * Update occupancy rate from POS system
     */
    public function updateOccupancyFromPOS(int $rate): bool
    {
        if ($rate < 0 || $rate > 100) {
            return false;
        }

        return $this->update([
            'occupancy_rate' => $rate,
            'last_occupancy_update' => now(),
        ]);
    }

    /**
     * Get available reservation time slots
     */
    public function getAvailableTimeSlots(): array
    {
        if (! $this->reservation_time_slots) {
            // Return default slots if not set
            return [
                ['start' => '10:00', 'end' => '23:00', 'slot_duration' => 60],
            ];
        }

        return $this->reservation_time_slots;
    }

    /*
    |--------------------------------------------------------------------------
    | SaaS Subscription Relations
    |--------------------------------------------------------------------------
    */

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['active', 'trial'])
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->latest('starts_at');
    }

    /**
     * Get the currently active package.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function getActivePackageAttribute()
    {
        return $this->activeSubscription?->package;
    }

    /**
     * Check if business has access to a feature.
     * Usage: $business->hasFeature('pos_access')
     */
    public function hasFeature($key)
    {
        $package = $this->active_package;
        if (! $package) {
            return false;
        }

        return $package->hasFeature($key);
    }

    /**
     * Get value of a specific feature.
     */
    public function getFeatureValue($key, $default = null)
    {
        $package = $this->active_package;
        if (! $package) {
            return $default;
        }

        return $package->features[$key] ?? $default;
    }

    /**
     * Get a specific setting value.
     */
    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Check if reservations are enabled globally.
     */
    public function isReservationsEnabled(): bool
    {
        return (bool) $this->getSetting('reservations_enabled', true);
    }

    /**
     * Check if QR payments are enabled.
     */
    public function isQrPaymentsEnabled(): bool
    {
        return (bool) $this->getSetting('qr_payments_enabled', true);
    }

    /**
     * Check if reservations are automatically confirmed.
     */
    public function isAutoConfirmEnabled(): bool
    {
        return (bool) $this->getSetting('auto_confirm', true);
    }

    /**
     * Check if loyalty program is active.
     */
    public function isLoyaltyEnabled(): bool
    {
        return (bool) $this->getSetting('loyalty_enabled', true);
    }

    /**
     * Check if waiter call via QR is enabled.
     */
    public function isWaiterCallEnabled(): bool
    {
        return (bool) $this->getSetting('qr_waiter_call', true);
    }

    /**
     * Check if business is in busy mode.
     */
    public function isBusyMode(): bool
    {
        return (bool) $this->getSetting('busy_mode', false);
    }

    /**
     * Get the cancellation window in hours.
     */
    public function getCancellationWindow(): int
    {
        return (int) $this->getSetting('cancellation_limit', 24);
    }

    /**
     * Get the minimum advance booking time in hours.
     */
    public function getMinAdvanceBookingTime(): int
    {
        return (int) $this->getSetting('min_advance_hours', 0);
    }

    /**
     * Get the maximum booking ahead days.
     */
    public function getMaxBookingAheadDays(): int
    {
        return (int) $this->getSetting('max_ahead_days', 30);
    }

    /**
     * Get the primary image URL for the business.
     * Fallback: Logo -> First Gallery Image -> Category Placeholder -> Default
     */
    public function getImageUrl($thumb = false): string
    {
        // 0. Check Blob
        if ($this->image_blob) {
            return route('api.pos.image', ['id' => $this->id, 'type' => 'business', 't' => $this->updated_at?->timestamp]);
        }

        // 1. Check Logo
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http')) {
                return $this->logo;
            }
            return asset('storage/' . $this->logo);
        }

        // 2. Check Gallery
        $firstImage = $this->images->first();
        if ($firstImage) {
            return $thumb ? $firstImage->thumb_url : $firstImage->url;
        }

        // 3. Fallback to Category/Default
        $category = $this->businessCategory ?: $this->category;
        $categorySlug = ($category && is_object($category)) ? $category->slug : 'general';
        return asset("images/placeholders/business-{$categorySlug}.jpg");
    }
}
