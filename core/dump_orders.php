<?php

include 'vendor/autoload.php';
putenv('DB_HOST=127.0.0.1');
$app = include_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$orders = Order::with('items')->latest()->take(5)->get();

foreach ($orders as $order) {
    echo "Order ID: {$order->id}, Status: {$order->status}, Total: {$order->total_amount}, Paid: {$order->paid_amount}\n";
    foreach ($order->items as $item) {
        echo "  - Item: {$item->name}, Qty: {$item->quantity}, Unit: {$item->unit_price}, Total: {$item->total_price}, Status: {$item->status}\n";
    }
    echo "---------------------------------\n";
}
