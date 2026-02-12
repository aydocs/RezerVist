<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class PosIntegrationController extends Controller
{
    /**
     * Update occupancy rate from POS system
     */
    public function updateOccupancy(Request $request)
    {
        $validated = $request->validate([
            'occupancy_rate' => 'required|integer|min:0|max:100',
            'business_id' => 'sometimes|exists:businesses,id' // Optional, can be derived from token
        ]);

        // Get business from request attributes (set by CheckSubscription middleware) 
        // or fallback to authenticated user's business
        $business = $request->attributes->get('business') ?? ($request->user() ? ($request->user()->business ?? $request->user()->ownedBusiness) : null);

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'İşletme bulunamadı.'
            ], 404);
        }

        $success = $business->updateOccupancyFromPOS($validated['occupancy_rate']);

        if ($success) {
            // Log activity
            \App\Models\ActivityLog::logActivity(
                'pos_occupancy_update',
                "POS sisteminden doluluk güncellendi: {$validated['occupancy_rate']}%",
                ['business_id' => $business->id, 'rate' => $validated['occupancy_rate']]
            );

            return response()->json([
                'success' => true,
                'message' => 'Doluluk oranı güncellendi.',
                'data' => [
                    'occupancy_rate' => $business->fresh()->occupancy_rate,
                    'last_update' => $business->fresh()->last_occupancy_update
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Doluluk oranı güncellenemedi.'
        ], 400);
    }

    /**
     * Get current occupancy status
     */
    public function getOccupancy(Request $request)
    {
        $business = $request->attributes->get('business') ?? ($request->user() ? ($request->user()->business ?? $request->user()->ownedBusiness) : null);

        return response()->json([
            'success' => true,
            'data' => [
                'occupancy_rate' => $business->occupancy_rate,
                'last_update' => $business->last_occupancy_update,
                'business_name' => $business->name
            ]
        ]);
    }
}
