<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Subscription;

$email = 'owner1@test.com';
$user = User::where('email', $email)->first();

if (!$user) {
    die("User not found\n");
}

echo "User ID: " . $user->id . "\n";
echo "User Role: " . $user->role . "\n";
echo "User Business ID (column): " . $user->business_id . "\n";

$ownedBusiness = $user->ownedBusiness;
echo "Owned Business ID: " . ($ownedBusiness->id ?? 'None') . "\n";

$businessId = $user->business_id ?? $ownedBusiness->id;
$business = Business::find($businessId);

if (!$business) {
    die("Business not found for ID $businessId\n");
}

echo "Working with Business: " . $business->name . " (ID: " . $business->id . ")\n";

$allSubscriptions = Subscription::where('business_id', $business->id)->get();
echo "All Subscriptions for Business " . $business->id . ":\n";
foreach ($allSubscriptions as $sub) {
    echo "- ID: {$sub->id}, Package ID: {$sub->package_id} ({$sub->package->name}), Status: {$sub->status}, Starts: {$sub->starts_at}, Ends: " . ($sub->ends_at ?? 'NULL') . "\n";
}

$activeSub = $business->activeSubscription;
echo "Laravel sees Active Subscription: " . ($activeSub ? "ID {$activeSub->id}, Package ID {$activeSub->package_id} ({$activeSub->package->name})" : "NONE") . "\n";
