<?php

use App\Models\Business;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ownerEmail = 'owner1@test.com';
$user = User::where('email', $ownerEmail)->first();
$business = Business::where('owner_id', $user->id)->first();

echo 'Business: '.$business->name."\n";

$proPackage = Package::where('slug', 'pro')->first();
if (! $proPackage) {
    echo "Pro package not found!\n";

    // Create Pro package if missing (unlikely but safe)
    $proPackage = Package::create([
        'name' => 'Pro (Rezervasyon + POS)',
        'slug' => 'pro',
        'price_monthly' => 1000,
        'price_yearly' => 10000,
        'features' => json_encode(['pos_access', 'reservation_management', 'table_management']), // Fallback features
    ]);
    echo "Created Pro package.\n";
}

echo 'Switching to package: '.$proPackage->name.' (ID: '.$proPackage->id.")\n";

$subscription = $business->activeSubscription;

if ($subscription) {
    $subscription->update([
        'package_id' => $proPackage->id,
        'name' => $proPackage->name,
    ]);
    echo "Updated existing subscription to Pro.\n";
} else {
    Subscription::create([
        'business_id' => $business->id,
        'package_id' => $proPackage->id,
        'name' => $proPackage->name,
        'price' => $proPackage->price_monthly,
        'status' => 'active',
        'starts_at' => now(),
        'ends_at' => now()->addYears(10),
    ]);
    echo "Created new Pro subscription.\n";
}

// Add features to package if using pivot (depending on implementation, but likely JSON or pivot)
// Assuming Package model has features() relation or features column.
// Based on debug error, features might be a relation that failed.
// Let's check hasFeature implementation in Business model again.
// $package->hasFeature($key) -> checks features.

echo "Done. Please try logging in again.\n";
