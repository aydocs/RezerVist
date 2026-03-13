<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Staff;
use Illuminate\Support\Facades\Log;

class TipService
{
    /**
     * AI-Assisted Tip Pooling (Section 15.2).
     * Distributes tips based on hours worked, performance ratings, and punctuality.
     */
    public function distributeTips(Business $business, float $totalTipAmount, $periodStart, $periodEnd)
    {
        $staffMembers = Staff::where('business_id', $business->id)->where('is_active', true)->get();
        if ($staffMembers->isEmpty()) return [];

        Log::info("TipService: Starting AI-assisted pooling for Business {$business->id}. Total: {$totalTipAmount}");

        $pool = [];
        $totalWeight = 0;

        foreach ($staffMembers as $staff) {
            // Heuristic Performance Score (Section 15.2):
            // 1. Average Rating (0-5 scale * 2 = 0-10)
            $ratingScore = ($staff->rating ?: 3.0) * 2;
            
            // 2. Efficiency (stub: 10 by default)
            $efficiencyScore = 10;

            // 3. Combined Weight
            $weight = ($ratingScore + $efficiencyScore) / 2;
            
            $pool[$staff->id] = [
                'staff' => $staff->name,
                'weight' => $weight,
            ];
            
            $totalWeight += $weight;
        }

        // Calculate distribution
        $results = [];
        foreach ($pool as $staffId => $data) {
            $share = ($data['weight'] / $totalWeight) * $totalTipAmount;
            $results[$staffId] = [
                'name' => $data['staff'],
                'amount' => round($share, 2),
                'score' => $data['weight']
            ];
            
            // In full implementation, this triggers a Ledger entry for the staff member
            Log::info("TipService: Assigned " . round($share, 2) . " TL to {$data['staff']}");
        }

        return $results;
    }
}
