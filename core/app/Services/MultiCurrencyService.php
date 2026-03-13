<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MultiCurrencyService
{
    /**
     * Convert amount from one currency to another (Section 17.2).
     */
    public function convert(float $amount, string $from = 'TRY', string $to = 'USD'): float
    {
        if ($from === $to) return $amount;

        $rate = $this->getExchangeRate($from, $to);
        return round($amount * $rate, 2);
    }

    /**
     * Fetch real-time exchange rate (stub).
     */
    protected function getExchangeRate(string $from, string $to): float
    {
        $cacheKey = "rate_{$from}_{$to}";

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($from, $to) {
            Log::info("Currency: Fetching exchange rate {$from} -> {$to}");
            
            // In full implementation, this calls an API like fixer.io or exchange-rates.org
            $rates = [
                'TRY_USD' => 0.031,
                'TRY_EUR' => 0.028,
                'USD_TRY' => 32.25,
            ];

            return $rates["{$from}_{$to}"] ?? 1.0;
        });
    }
}
