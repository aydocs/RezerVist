<?php

use App\Models\Business;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = 'N41inQ0akyIZpQuIp0m9QOJE9cK30oB1oaRLMbzo691CsKZaiJktbCTq1p8voYs1';

echo "Searching for business with key: $key\n";

$business = Business::where('pos_api_token', $key)
    ->orWhere('license_key', $key)
    ->first();

if (! $business) {
    echo "Business NOT FOUND!\n";

    // Check all businesses
    $all = Business::all(['id', 'name', 'pos_api_token', 'license_key']);
    echo 'Total businesses: '.$all->count()."\n";
    foreach ($all as $b) {
        echo "ID: {$b->id}, Name: {$b->name}, Token: ".substr($b->pos_api_token, 0, 10).'..., Key: '.substr($b->license_key, 0, 10)."...\n";
    }
    exit;
}

echo "Business found: {$business->name} (ID: {$business->id})\n";
echo "--- Attributes ---\n";
print_r($business->getAttributes());

echo "\n--- Subscription Info ---\n";
echo 'subscription_status: '.($business->subscription_status ?? 'null')."\n";
echo 'subscription_ends_at: '.($business->subscription_ends_at ?? 'null')."\n";
echo 'subscription_end_date: '.($business->subscription_end_date ?? 'null')."\n";

echo "\n--- Active Subscription Relationship ---\n";
$sub = $business->activeSubscription;
if ($sub) {
    echo "Sub ID: {$sub->id}, Package ID: {$sub->package_id}, Starts: {$sub->starts_at}, Ends: {$sub->ends_at}, Status: {$sub->status}\n";
} else {
    echo "No active subscription found via relationship!\n";
}

echo "\n--- Features ---\n";
echo 'pos_access: '.($business->hasFeature('pos_access') ? 'Yes' : 'No')."\n";
