<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // List my favorites
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->with('business')->get();

        return response()->json($favorites);
    }

    // Toggle favorite
    public function toggle(Request $request, $businessId)
    {
        $business = Business::findOrFail($businessId);
        $user = $request->user();

        $exists = $user->favorites()->where('business_id', $businessId)->first();

        if ($exists) {
            $exists->delete();

            return response()->json([
                'message' => 'Favorilerden çıkarıldı',
                'favorited' => false,
            ]);
        } else {
            $user->favorites()->create(['business_id' => $businessId]);

            return response()->json([
                'message' => 'Favorilere eklendi',
                'favorited' => true,
            ]);
        }
    }

    // Sync guest favorites
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'business_ids' => 'required|array',
            'business_ids.*' => 'exists:businesses,id',
        ]);

        $user = $request->user();
        $syncedCount = 0;

        foreach ($validated['business_ids'] as $businessId) {
            $exists = $user->favorites()->where('business_id', $businessId)->exists();
            if (! $exists) {
                $user->favorites()->create(['business_id' => $businessId]);
                $syncedCount++;
            }
        }

        return response()->json([
            'message' => 'Favoriler senkronize edildi',
            'synced_count' => $syncedCount,
        ]);
    }
}
