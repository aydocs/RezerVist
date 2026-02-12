<?php

use App\Models\Business;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$business = Business::where('name', 'like', '%Deniz Mahsulleri%')->first();

if (!$business) {
    exit("Business not found.\n");
}

echo "Checking Menus for Business ID: " . $business->id . "\n";

$menus = \App\Models\Menu::where('business_id', $business->id)->get();

foreach ($menus as $menu) {
    echo "Menu ID: " . $menu->id . " - " . $menu->name . "\n";
    
    // Check specific fields
    $attributes = $menu->getAttributes();
    foreach ($attributes as $key => $value) {
        if (is_string($value)) {
            if (strpos($value, '\\') !== false) {
                 echo "  [WARNING] Backslash found in '$key': " . addcslashes($value, "\\") . "\n";
                 echo "  -> JSON Encoded: " . json_encode($value) . "\n";
            }
        }
    }
    
    // Check options casting
    if (isset($attributes['options'])) {
        $rawOptions = $attributes['options'];
        echo "  Raw Options: " . substr($rawOptions, 0, 50) . "...\n";
        // Check if raw options has invalid escapes
        if (strpos($rawOptions, '\\') !== false) {
             echo "  [WARNING] Backslash in raw options JSON!\n";
             // Extract context
             $pos = strpos($rawOptions, '\\');
             echo "  Context: " . substr($rawOptions, max(0, $pos - 10), 30) . "\n";
        }
    }
}
