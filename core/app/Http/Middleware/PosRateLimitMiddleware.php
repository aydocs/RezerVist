<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PosRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $licenseKey = $request->header('Authorization');

        // Rate limit by IP (5 attempts per minute)
        $ipKey = 'pos_rate_limit:ip:'.$ip;
        $ipAttempts = Cache::get($ipKey, 0);

        if ($ipAttempts >= 5) {
            Log::warning('POS Rate Limit: Too many attempts from IP', [
                'ip' => $ip,
                'attempts' => $ipAttempts,
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Çok fazla deneme yapıldı. Lütfen 1 dakika bekleyin.',
            ], 429);
        }

        // Rate limit by license key (10 attempts per hour)
        if ($licenseKey) {
            $keyHash = md5($licenseKey);
            $keyKey = 'pos_rate_limit:key:'.$keyHash;
            $keyAttempts = Cache::get($keyKey, 0);

            if ($keyAttempts >= 10) {
                Log::warning('POS Rate Limit: Too many attempts for license key', [
                    'key_hash' => $keyHash,
                    'ip' => $ip,
                    'attempts' => $keyAttempts,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Bu lisans anahtarı için çok fazla deneme yapıldı. Lütfen 1 saat bekleyin.',
                ], 429);
            }

            // Increment key attempts (1 hour expiry)
            Cache::put($keyKey, $keyAttempts + 1, now()->addHour());
        }

        // Increment IP attempts (1 minute expiry)
        Cache::put($ipKey, $ipAttempts + 1, now()->addMinute());

        $response = $next($request);

        // If successful activation, clear the counters
        if ($response->status() === 200) {
            Cache::forget($ipKey);
            if ($licenseKey) {
                Cache::forget('pos_rate_limit:key:'.md5($licenseKey));
            }
        }

        return $response;
    }
}
