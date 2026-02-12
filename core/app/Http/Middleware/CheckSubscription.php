<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        $business = null;

        // 1. Identify the business
        if ($request->user()) {
            $business = $request->user()->business ?? $request->user()->ownedBusiness;
        } elseif ($request->business) {
            $business = $request->business;
        } elseif ($request->attributes->get('business')) {
            $business = $request->attributes->get('business');
        } elseif ($request->header('X-Business-ID')) {
            $business = \App\Models\Business::find($request->header('X-Business-ID'));
        }

        if (!$business) {
            return $next($request); // If no business, let other auth handle it
        }

        // Add business to request attributes for controllers
        $request->attributes->set('business', $business);

        // 1.5. Admins bypass subscription checks
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // 2. Check Subscription Status
        $subscription = $business->activeSubscription;

        if (!$subscription) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Aktif bir aboneliğiniz bulunmamaktadır.',
                    'code' => 'SUBSCRIPTION_REQUIRED'
                ], 403);
            }
            return redirect()->route('vendor.billing.index')->with('error', 'Bu özelliği kullanmak için aktif bir aboneliğiniz olmalıdır.');
        }

        // 3. Grace Period Check (Allow 3 days past expiration)
        if ($subscription->status == 'expired' || ($subscription->ends_at && $subscription->ends_at->isPast())) {
            $graceDays = 3;
            $graceEnd = $subscription->ends_at ? $subscription->ends_at->addDays($graceDays) : null;

            if (!$graceEnd || $graceEnd->isPast()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Abonelik süreniz dolmuştur.',
                        'code' => 'SUBSCRIPTION_EXPIRED'
                    ], 403);
                }
                return redirect()->route('vendor.billing.index')->with('error', 'Abonelik süreniz dolmuştur. Devam etmek için paket yenilemelisiniz.');
            }
        }

        // 3. Check specific feature (e.g., pos_access)
        if ($feature && !$business->hasFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Paketiniz bu özelliği desteklememektedir.',
                    'code' => 'FEATURE_NOT_PERMITTED'
                ], 403);
            }
            return redirect()->back()->with('error', 'Paketiniz bu özelliği desteklememektedir.');
        }

        return $next($request);
    }
}
