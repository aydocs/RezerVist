<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Support\Facades\Log;

class FeedbackService
{
    /**
     * Handle "In-Moment" Feedback (Section 16.2).
     * Triggers critical alerts for negative feedback submitted via QR.
     */
    public function processInMomentFeedback(Review $review)
    {
        if (!$review->is_in_moment) return;

        Log::info("FeedbackService: Processing In-Moment Feedback for Business {$review->business_id}");

        // Critical Alert Logic (Section 16.2)
        if ($review->rating <= 2) {
            Log::critical("ALERT: Negative In-Moment Feedback! Business {$review->business_id}, Table {$review->resource_id}, Rating {$review->rating}");
            
            // In a real implementation:
            // 1. Send SMS to Business Owner
            // 2. Trigger Push Notification to Manager
            // 3. Log to Critical BI dashboard
        }
    }

    /**
     * Sentiment Analysis Stub for External Reviews (Google/TripAdvisor).
     */
    public function analyzeExternalSentiment(string $text)
    {
        // Integration with external NLP APIs would go here.
        // Returning a random score for demonstration.
        return [
            'sentiment' => 'positive',
            'score' => 0.85,
            'entities' => ['food', 'service']
        ];
    }
}
