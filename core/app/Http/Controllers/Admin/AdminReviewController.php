<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', Review::STATUS_APPROVED); // Default to approved (live) reviews
        $showReported = $request->get('reported', false);
        
        $reviews = Review::with(['user', 'business'])
            ->when($showReported, function($query) {
                return $query->where('is_reported', true);
            })
            ->when(!$showReported && $status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews', 'status', 'showReported'));
    }

    /**
     * Approve a review.
     */
    public function approve(Review $review)
    {
        $review->update(['status' => Review::STATUS_APPROVED]);
        
        // trigger specific rating update just in case, though Model observer should handle it
        $review->business->updateRating();

        return back()->with('success', 'Yorum onaylandı.');
    }

    /**
     * Reject a review.
     */
    public function reject(Review $review)
    {
        $review->update(['status' => Review::STATUS_REJECTED]);
        
        // If it was approved before, we need to update rating
        $review->business->updateRating();

        return back()->with('success', 'Yorum reddedildi.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Yorum tamamen silindi.');
    }

    /**
     * Keep a reported review as safe.
     */
    public function keep(Review $review)
    {
        $review->update([
            'is_reported' => false,
            'reported_at' => null,
            'status' => Review::STATUS_APPROVED
        ]);
        
        return back()->with('success', 'Yorum güvenli olarak işaretlendi.');
    }

    /**
     * Toggle block for a user.
     */
    public function toggleBlock(User $user)
    {
        $user->is_review_blocked = !$user->is_review_blocked;
        $user->save();

        $statusMessage = $user->is_review_blocked ? 'kısıtlandı' : 'kısıtlaması kaldırıldı';
        return back()->with('success', "Kullanıcının yorum yapma yetkisi {$statusMessage}.");
    }
}
