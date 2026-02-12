<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'owner2@test.com')->first();
if ($user) {
    echo 'User ID: '.$user->id."\n";
    echo 'Business ID (user table): '.($user->business_id ?? 'NULL')."\n";
    $b = $user->ownedBusiness;
    echo 'Owned Business: '.($b ? $b->name : 'NONE')."\n";
    if ($b) {
        echo 'Business Status: '.$b->subscription_status."\n";
        echo 'Active Package: '.($b->active_package ? $b->active_package->name : 'NONE')."\n";
    }
} else {
    echo "USER NOT FOUND\n";
}
