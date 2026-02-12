<?php

use App\Models\Business;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$business = Business::where('name', 'like', '%Deniz Mahsulleri%')->with(['reviews', 'resources', 'menus'])->first();

if (!$business) {
    echo "Business not found.\n";
    exit;
}

echo "Found Business: " . $business->name . " (ID: " . $business->id . ")\n";

$json = $business->toJson();
echo "JSON Fetch Success (if no error above).\n";

// specific check for description or potential long fields
echo "Description length: " . strlen($business->description) . "\n";
// Dump a snippet of description to see if there are backslashes
if (strpos($business->description, '\\') !== false) {
    echo "WARNING: Description contains backslash!\n";
    echo substr($business->description, max(0, strpos($business->description, '\\') - 20), 50) . "\n";
}

// Check for other text fields
foreach ($business->getAttributes() as $key => $value) {
    if (is_string($value) && strpos($value, '\\') !== false) {
         echo "WARNING: Field '$key' contains backslash!\n";
    }
}

// Validation of JSON encoding
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Encoding Error: " . json_last_error_msg() . "\n";
} else {
    echo "JSON Encoding looks valid from PHP side.\n";
}
