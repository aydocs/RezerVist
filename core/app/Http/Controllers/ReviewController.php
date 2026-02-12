<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // List reviews for a business
    public function index($businessId)
    {
        $business = Business::findOrFail($businessId);
        $reviews = $business->reviews()->approved()->with('user')->latest()->paginate(10);

        return response()->json($reviews);
    }

    // Add a review
    public function store(\App\Http\Requests\StoreReviewRequest $request, Business $business)
    {
        // Authorization is handled by FormRequest
        $validated = $request->validated();

        $user = $request->user();

        $review = $business->reviews()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => \App\Models\Review::STATUS_APPROVED, // Now auto-approved for eligible users
        ]);

        // Update overall business rating
        if (method_exists($business, 'updateRating')) {
            $business->updateRating();
        }

        return response()->json($review);
    }

    /**
     * Report a review (Business owner/Staff side)
     */
    public function report(Request $request, \App\Models\Review $review)
    {
        $user = $request->user();

        // Ensure only business owner or staff can report
        if ($review->business_id !== $user->business_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Bu işlemi yapmaya yetkiniz yok.'], 403);
        }

        $review->update([
            'is_reported' => true,
            'reported_at' => now(),
        ]);

        return response()->json(['message' => 'Yorum bildirildi. İnceleme sonrası gerekirse kaldırılacaktır.']);
    }
}
