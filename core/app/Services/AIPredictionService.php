<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class AIPredictionService
{
    /**
     * Calculate No-Show Risk (NSR) for a reservation.
     * Section 19.1: Scoring based on past behavior and timing.
     */
    public function getNoShowRisk(Reservation $reservation): array
    {
        $user = $reservation->user;
        if (!$user) {
            return ['score' => 20, 'level' => 'Low', 'reason' => 'Guest user'];
        }

        Log::info("AI: Predicting NSR for User {$user->id} on Reservation {$reservation->id}");

        $totalReservations = $user->reservations()->count();
        $noShows = $user->reservations()->where('status', 'no_show')->count();
        $cancellations = $user->reservations()->where('status', 'cancelled')->count();

        // 1. Behavior Factor
        $historyScore = 0;
        if ($totalReservations > 0) {
            $historyScore = ($noShows / $totalReservations) * 70; // No-shows are heavy
            $historyScore += ($cancellations / $totalReservations) * 30; // Cancellations are medium
        }

        // 2. Timing Factor (Section 19.2)
        // High group reservations on weekends are riskier
        $timingScore = 0;
        if ($reservation->guest_count > 6) $timingScore += 15;
        if ($reservation->start_time->isWeekend()) $timingScore += 10;

        $totalScore = min(100, $historyScore + $timingScore);

        $level = 'Low';
        if ($totalScore > 40) $level = 'Medium';
        if ($totalScore > 75) $level = 'High';

        return [
            'score' => round($totalScore, 2),
            'level' => $level,
            'reason' => $this->getRiskReason($historyScore, $timingScore)
        ];
    }

    protected function getRiskReason($history, $timing): string
    {
        if ($history > 50) return 'Past no-show patterns detected';
        if ($timing > 20) return 'High guest count on peak hours';
        return 'Normal behavior';
    }
}
