<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    /**
     * Award points to a user and check for level/badge upgrades.
     */
    public function awardPoints(User $user, int $amount, string $reason = 'activity')
    {
        $user->increment('points', $amount);
        
        Log::info("Loyalty: Awarded {$amount} points to User {$user->id} for {$reason}");

        $this->checkForNewBadges($user);
        
        return $user->points;
    }

    /**
     * Check if the user qualifies for any new badges.
     */
    public function checkForNewBadges(User $user)
    {
        $unearnedBadges = Badge::whereDoesntHave('users', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        foreach ($unearnedBadges as $badge) {
            if ($user->points >= $badge->points_required) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                Log::info("Loyalty: User {$user->id} earned badge: {$badge->name}");
                
                // Trigger notification here in full implementation
            }
        }
    }

    /**
     * Get user's current loyalty level status.
     */
    public function getLevelStatus(User $user)
    {
        $points = $user->points;

        $levels = [
            ['name' => 'Bronze', 'min' => 0],
            ['name' => 'Silver', 'min' => 1000],
            ['name' => 'Gold', 'min' => 5000],
            ['name' => 'Platinum', 'min' => 15000],
            ['name' => 'Black', 'min' => 50000],
        ];

        $currentLevel = 'Bronze';
        $nextLevel = null;

        foreach ($levels as $index => $level) {
            if ($points >= $level['min']) {
                $currentLevel = $level['name'];
                $nextLevel = $levels[$index + 1] ?? null;
            }
        }

        return [
            'current' => $currentLevel,
            'next' => $nextLevel,
            'points' => $points,
            'points_to_next' => $nextLevel ? ($nextLevel['min'] - $points) : 0,
            'progress_percent' => $nextLevel ? round(($points / $nextLevel['min']) * 100) : 100,
        ];
    }
}
