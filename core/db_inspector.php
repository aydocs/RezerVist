<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Resource;

$masaName = 'Masa 1';

// Force 127.0.0.1 for local dev on Windows to avoid connection refused
\Illuminate\Support\Facades\Config::set('database.connections.mysql.host', '127.0.0.1');
\Illuminate\Support\Facades\DB::purge('mysql');

$resource = Resource::where('name', $masaName)->first();

if (!$resource) {
    die("Resource $masaName not found\n");
}

$order = Order::where('resource_id', $resource->id)
    ->where('status', 'active')
    ->latest()
    ->first();

if (!$order) {
    die("No active order found for $masaName\n");
}

echo "Order ID: {$order->id}\n";
echo "Order Total Amount (column): {$order->total_amount}\n";
echo "Order Paid Amount (column): {$order->paid_amount}\n";
echo "\nItems:\n";

$items = OrderItem::where('order_id', $order->id)->get();
foreach ($items as $item) {
    echo "- ID: {$item->id}, Name: {$item->name}, Qty: {$item->quantity}, Unit: {$item->unit_price}, Total: {$item->total_price}, Status: '{$item->status}'\n";
}

$sumTotal = $items->whereNotIn('status', ['cancelled', 'deleted'])->sum(function($i) { return $i->unit_price * $i->quantity; });
$sumPaid = $items->where('status', 'completed')->sum(function($i) { return $i->unit_price * $i->quantity; });

echo "\nManual Recalculation:\n";
echo "Sum Total (not cancelled/deleted): $sumTotal\n";
echo "Sum Paid (status == completed): $sumPaid\n";
echo "Balance: " . ($sumTotal - $sumPaid) . "\n";
