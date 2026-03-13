<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class QuestService
{
    /**
     * Generate or trigger AI-driven quests for a user.
     * Section 20.3: Personal targets based on behavior.
     */
    public function updateQuests(User $user)
    {
        Log::info("QuestService: Analyzing behavior for User {$user->id}");

        // In a real AI implementation, this would call an LLM or use ML clustering.
        // For now, we implement high-fidelity heuristic stubs as per the spec.
        
        $quests = [];

        // 1. Visit Frequency Target
        if ($user->reservations()->count() < 5) {
            $quests[] = [
                'id' => 'first_timer_boost',
                'title' => 'Gurme Yolculuğu Başlıyor',
                'description' => 'Toplam 5 rezervasyon tamamla ve 500 bonus puan kazan!',
                'target' => 5,
                'current' => $user->reservations()->count(),
                'reward_points' => 500
            ];
        }

        // 2. High Spender (Section 12.3 crossover)
        if ($user->balance > 1000) {
             $quests[] = [
                'id' => 'platinum_experience',
                'title' => 'Premium Müşteri Ayrıcalığı',
                'description' => 'Tek seferde 2000 TL ve üzeri harcama yap, özel rozeti kap!',
                'reward_badge' => 'premium_spender'
            ];
        }

        return $quests;
    }
}
