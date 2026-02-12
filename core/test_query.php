<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Business;
use App\Models\OrderItem;

try {
    $business = Business::first();
    if (!$business) {
        echo "No business found.\n";
        exit;
    }
    echo "Testing with business ID: " . $business->id . "\n";
    $startDate = now()->startOfDay();
    $endDate = now()->endOfDay();

    $itemsQuery = OrderItem::whereHas('order', function($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
              ->whereBetween('updated_at', [$startDate, $endDate]);
        })
        ->where('status', 'completed');
    
    // echo "Query SQL: " . $itemsQuery->toSql() . "\n";
    
    $total = $itemsQuery->sum('total_price');
    echo "Total Sales: " . $total . "\n";

    // Test breakdown query
    echo "Testing breakdown query...\n";
    try {
        $cashTotal = (float)\App\Models\OrderItem::whereHas('order', function($q) use ($business, $startDate, $endDate) {
                $q->where('business_id', $business->id)
                  ->where(function($qq) {
                      $qq->where('payment_method', 'cash')
                        ->orWhere('payment_method', 'nakit');
                  })
                  ->whereBetween('updated_at', [$startDate, $endDate]);
            })
            ->where('status', 'completed')
            ->sum('total_price');
        echo "Cash Total: " . $cashTotal . "\n";
    } catch (\Exception $e) {
        echo "Breakdown Error: " . $e->getMessage() . "\n";
    }

    echo "Success.\n";

} catch (\Exception $e) {
    echo "Global Error: " . $e->getMessage() . "\n";
}
?>
