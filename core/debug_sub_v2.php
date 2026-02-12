<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Business;
use App\Models\Subscription;
use App\Models\User;

$email = 'owner1@test.com';
$user = User::where('email', $email)->first();

if (! $user) {
    exit("User not found: $email\n");
}

echo "--- DEBUG REPORT ---\n";
echo "User: {$user->name} (ID: {$user->id})\n";
echo 'User->business_id (column): '.($user->business_id ?? 'NULL')."\n";

$ownedBusiness = $user->ownedBusiness;
echo 'User->ownedBusiness: '.($ownedBusiness ? $ownedBusiness->name.' (ID: '.$ownedBusiness->id.')' : 'NONE')."\n";

$currentBusinessId = $user->business_id ?? ($ownedBusiness ? $ownedBusiness->id : null);

if (! $currentBusinessId) {
    exit("No business associated with this user.\n");
}

$business = Business::find($currentBusinessId);
echo 'Effective Business: '.$business->name.' (ID: '.$business->id.")\n";

$activeSub = $business->activeSubscription;
if ($activeSub) {
    echo "Active Subscription Found:\n";
    echo "  - ID: {$activeSub->id}\n";
    echo "  - Package: {$activeSub->package->name} (ID: {$activeSub->package_id})\n";
    echo "  - Status: {$activeSub->status}\n";
    echo "  - Starts: {$activeSub->starts_at}\n";
    echo "  - Ends: {$activeSub->ends_at}\n";
} else {
    echo "No Active Subscription found via Business->activeSubscription relationship.\n";
}

echo "\nAll Subscriptions for Business ID {$business->id}:\n";
$subs = Subscription::where('business_id', $business->id)->get();
foreach ($subs as $s) {
    echo "- [ID: {$s->id}] Package: {$s->package_id}, Status: {$s->status}, Starts: {$s->starts_at}\n";
}
