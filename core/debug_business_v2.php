<?php

use App\Models\Business;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the business
$business = Business::where('name', 'like', '%Deniz Mahsulleri%')
    ->with(['reviews.user', 'resources', 'menus']) // Correct relations
    ->first();

if (!$business) {
    echo "Business not found.\n";
    exit;
}

echo "Found Business: " . $business->name . " (ID: " . $business->id . ")\n";

try {
    // We use encoding options to force check for invalid UTF8
    $json = json_encode($business->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
    
    if ($json === false) {
        echo "json_encode FAILED: " . json_last_error_msg() . "\n";
        exit;
    }

    echo "JSON Length: " . strlen($json) . "\n";
    
    // Check near offset 57396
    if (strlen($json) > 57300) {
        $start = 57350;
        $len = 100;
        if ($start + $len > strlen($json)) {
             $len = strlen($json) - $start;
        }
        echo "\n--- CONTEXT AROUND OFFSET 57396 ---\n";
        echo substr($json, $start, $len) . "\n";
        echo "---------------------------------\n";
    } else {
        echo "JSON is smaller than reported offset (" . strlen($json) . " vs 57396). The offset might be different in this environment or data changed.\n";
    }

    // Check for "Unrecognized string escape" candidates: \ followed by something bad
    // But json_encode usually handles this.
    // Maybe double encoded JSON?
    
    // Deep scan for strings looking like "C:\"
    array_walk_recursive($business->toArray(), function($item, $key) {
        if (is_string($item)) {
            if (preg_match('/\\\(?!["\\\/bfnrtu])/', $item)) {
                // This regex checks for backslash NOT followed by valid escape chars. 
                // BUT in a PHP string in memory, a single backslash is just a backslash. 
                // json_encode will escape it to \\.
                // So this check is checking if the raw data has a backslash.
                // If raw data has "C:\foo", json_encode makes "C:\\foo", which is valid.
                // So this is NOT the issue unless json_encode is bypassed or failed.
                // However, let's see if we find any weird backslashes.
                // echo "Generic Backslash found in col '$key': $item\n";
            }
            
            // Check for control characters
            if (preg_match('/[[:cntrl:]]/', $item)) {
                // Formatting causes newlines, tabs etc.
                // echo "Control char in '$key'\n";
            }
        }
    });

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
