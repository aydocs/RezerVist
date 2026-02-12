<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionService
{
    /**
     * Assign or upgrade a plan for a business.
     */
    public function assignPlan(Business $business, Package $package, int $months = 1, string $method = 'manual'): Subscription
    {
        $startsAt = now();
        $endsAt = now()->addMonths($months);

        // If has active subscription, decide if we extend or overwrite
        $current = $business->activeSubscription;
        if ($current && $current->package_id == $package->id && $current->ends_at > now()) {
            $startsAt = $current->ends_at; // Extend the current one
            $endsAt = Carbon::parse($current->ends_at)->addMonths($months);
            $current->update(['status' => 'extended']); // Mark old one
        } elseif ($current) {
            $current->update(['status' => 'cancelled']); // Replace old tier
        }

        $subscription = Subscription::create([
            'business_id' => $business->id,
            'package_id' => $package->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'payment_method' => $method,
        ]);

        // Create Invoice Record
        $this->createInvoice($business, $subscription, $package->price_monthly * $months, $method);

        // Sync local business cache fields
        $business->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $endsAt,
        ]);

        return $subscription;
    }

    /**
     * Create an invoice for a transaction.
     */
    public function createInvoice(Business $business, ?Subscription $subscription, float $amount, string $method): Invoice
    {
        return Invoice::create([
            'business_id' => $business->id,
            'subscription_id' => $subscription?->id,
            'invoice_number' => 'INV-'.strtoupper(Str::random(10)),
            'amount' => $amount,
            'status' => $amount > 0 ? 'paid' : 'paid', // For now auto-mark paid for manual/free
            'paid_at' => now(),
            'payment_method' => $method,
            'billing_name' => $business->name,
        ]);
    }

    /**
     * Periodic check to mark expired subscriptions.
     */
    public function checkExpirations()
    {
        $expired = Subscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->get();

        foreach ($expired as $sub) {
            $sub->update(['status' => 'expired']);

            // Also update business record
            $sub->business->update(['subscription_status' => 'expired']);
        }

        return $expired->count();
    }
}
