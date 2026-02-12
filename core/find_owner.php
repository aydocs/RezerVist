<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'owner2@test.com')->first();
if ($user) {
    if ($user->business) {
        echo "Business: " . $user->business->name . "\n";
        echo "ID: " . $user->business->id . "\n";
    } else {
        echo "No business associated.\n";
    }
} else {
    echo "User not found.\n";
}
