<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class I18nService
{
    /**
     * Translate text using AI (stub).
     * Section 17.1: AI-powered localized content.
     */
    public function translate(string $text, string $targetLocale, string $sourceLocale = 'tr'): string
    {
        if ($targetLocale === $sourceLocale) return $text;

        $cacheKey = "trans_" . md5($text) . "_" . $targetLocale;

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text, $targetLocale) {
            Log::info("I18n: AI Translating '{$text}' to {$targetLocale}");
            
            // In full implementation, this calls OpenAI or Google Translate API
            // For now, we return a mock translation
            return "[{$targetLocale}] " . $text;
        });
    }

    /**
     * Get active languages.
     */
    public function getActiveLanguages()
    {
        return Language::where('is_active', true)->get();
    }
}
