<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AggregatorHubService
{
    /**
     * Map an external order to Rezervist internal Order structure.
     */
    public function receiveExternalOrder(Business $business, array $payload, string $source)
    {
        Log::info("External Order Received from {$source} for Business {$business->id}");

        // 1. Validate Business Integration Status
        if (!$business->hasFeature('aggregator_access')) {
            Log::warning("Business {$business->id} does not have access to Aggregator Hub.");
            return null;
        }

        // 2. Map Payload to Order (Simplified for now)
        // In a real scenario, this would involve different адаптерs for Getir/Yemeksepeti APIs.
        $order = Order::create([
            'business_id' => $business->id,
            'source' => $source, // getting this from payload in future
            'status' => 'pending',
            'total_amount' => $payload['total'] ?? 0,
            'meta_data' => [
                'external_id' => $payload['id'] ?? null,
                'customer_name' => $payload['customer']['name'] ?? 'External Customer',
            ]
        ]);

        return $order;
    }

    /**
     * Sync stock 86'ing across all platforms (Section 14.1)
     */
    public function syncStockOut(Business $business, $menuItem)
    {
        Log::info("Syncing Stock OUT for Menu Item {$menuItem->id} across aggregators.");
        
        // This would call external APIs for Getir, Yemeksepeti, etc.
        // Example: $this->getirService->updateStock($menuItem->external_id, 0);
        
        return true;
    }
}
