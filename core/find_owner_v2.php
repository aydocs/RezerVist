<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'owner2@test.com')->first();
if ($user) {
    echo 'User found: '.$user->name.' (ID: '.$user->id.")\n";

    // Check owned business
    $ownedBusiness = $user->ownedBusiness;
    if ($ownedBusiness) {
        echo 'Owned Business: '.$ownedBusiness->name.' (ID: '.$ownedBusiness->id.")\n";
    } else {
        echo "No owned business found via hasOne relationship.\n";

        // Check hasMany just in case
        $businesses = $user->businesses;
        if ($businesses->count() > 0) {
            echo "Owned Businesses (hasMany): \n";
            foreach ($businesses as $b) {
                echo '- '.$b->name.' (ID: '.$b->id.")\n";
            }
        } else {
            echo "No businesses found via hasMany relationship.\n";
        }
    }
} else {
    echo "User 'owner2@test.com' not found.\n";
}
