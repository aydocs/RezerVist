<?php

namespace App\Services;

use App\Models\Business;
use Carbon\Carbon;

class DynamicPricingService
{
    /**
     * Calculate the dynamic price for a reservation or order.
     * 
     * @param Business $business
     * @param Carbon $dateTime
     * @param int $guestCount
     * @return float
     */
    public function calculate(Business $business, Carbon $dateTime, int $guestCount): float
    {
        $basePrice = $business->price_per_person > 0 ? $business->price_per_person : 0;

        // 1. Pricing Strategy: Per Person vs Fixed
        if ($business->pricing_type === 'per_person') {
            $total = $basePrice * $guestCount;
        } else {
            $total = $basePrice;
        }

        // 2. Temporal Multipliers (Section 12.1)
        // Weekend Surcharge (20%)
        if ($dateTime->isWeekend()) {
            $total *= 1.2;
        }

        // 3. Surge Pricing: Real-time Occupancy (Section 12.2)
        $occupancyRate = $this->getOccupancyRate($business, $dateTime);
        
        if ($business->surge_threshold > 0 && $occupancyRate >= $business->surge_threshold) {
            $multiplier = $business->surge_multiplier ?: 1.5;
            $total *= $multiplier;
        }

        // 4. Happy Hour / Early Bird Discounts
        $discount = $this->getTemporalDiscount($business, $dateTime);
        if ($discount > 0) {
            $total *= (1 - ($discount / 100));
        }

        return round($total, 2);
    }

    /**
     * Get real-time or predicted occupancy rate.
     */
    protected function getOccupancyRate(Business $business, Carbon $dateTime): float
    {
        // For real-time sync, use the occupancy_rate updated by POS
        if ($dateTime->isToday() && $dateTime->diffInMinutes(now()) < 30) {
            return (float) $business->occupancy_rate;
        }

        // For future dates, calculate based on existing reservations
        $totalCapacity = $business->resources()->sum('capacity');
        if ($totalCapacity <= 0) return 0;

        $bookedGuests = $business->reservations()
            ->whereBetween('start_time', [
                $dateTime->copy()->subMinutes(60),
                $dateTime->copy()->addMinutes(60)
            ])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->sum('guest_count');

        return ($bookedGuests / $totalCapacity) * 100;
    }

    /**
     * Get discount based on BusinessHour special pricing.
     */
    protected function getTemporalDiscount(Business $business, Carbon $dateTime): int
    {
        $hour = $business->hours()
            ->where('day_of_week', $dateTime->dayOfWeek)
            ->where('is_closed', false)
            ->where('open_time', '<=', $dateTime->format('H:i'))
            ->where('close_time', '>=', $dateTime->format('H:i'))
            ->first();

        return $hour->discount_percent ?? 0;
    }
}
