<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class BIAnalyticsService
{
    /**
     * Generate Heatmap data points (Section 13.1)
     */
    public function getOccupancyHeatmap(Business $business, string $range = '7days')
    {
        return DB::table('reservations')
            ->select(
                DB::raw('strftime("%H", start_time) as hour'),
                DB::raw('strftime("%w", start_time) as day_of_week'),
                DB::raw('AVG(guest_count) as avg_guests')
            )
            ->where('business_id', $business->id)
            ->groupBy('hour', 'day_of_week')
            ->get();
    }

    /**
     * Menu Engineering Matrix (Section 13.2)
     * Categorizes items as: Star, Workhorse, Question Mark, Dog
     */
    public function analyzeMenuItems(Business $business)
    {
        // Standard menu engineering calculation
        // High Popularity / High Profit -> STAR
        // High Popularity / Low Profit -> WORKHORSE
        // Low Popularity / High Profit -> QUESTION MARK
        // Low Popularity / Low Profit -> DOG
        
        // This is a stub for the complex matrix logic
        return [
            'stars' => [],
            'workhorses' => [],
            'question_marks' => [],
            'dogs' => []
        ];
    }
}
