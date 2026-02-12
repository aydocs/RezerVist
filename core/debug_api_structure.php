<?php

use App\Models\Business;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$business = Business::where('name', 'like', '%Deniz Mahsulleri%')->with(['menus'])->first();

if (!$business) {
    exit("Business not found.\n");
}

echo "Business ID: " . $business->id . "\n";
echo "Menus Count: " . $business->menus->count() . "\n";

// Check the structure of menus
if ($business->menus->count() > 0) {
    echo "First Menu Item:\n";
    print_r($business->menus->first()->toArray());
}

// Check if there is a 'categories' relationship or attribute
// The mobile app expects 'categories' list with nested items.
// backend usually sends 'menus' list.
// If the backend sends 'menus', the mobile app 'categories' field will be null unless simple_map is used or backend has an append.

echo "\nJSON Output Snippet:\n";
$json = json_encode($business->load('menus'));
echo substr($json, 0, 500) . "...\n";
