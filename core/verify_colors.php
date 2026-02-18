<?php

use App\Models\Business;
use Illuminate\Support\Facades\DB;

// Simulate update
$business = Business::first();
if (!$business) {
    echo "NO BUSINESS FOUND\n";
    exit;
}

echo "Testing Color Update for: " . $business->name . "\n";
echo "Initial Color: " . ($business->menu_color ?? 'NULL') . "\n";

$testColor = "#2563eb"; // Blue
$business->update(['menu_color' => $testColor]);

$business->refresh();
echo "Updated Color: " . $business->menu_color . "\n";

if ($business->menu_color === $testColor) {
    echo "SUCCESS: Color saved correctly!\n";
} else {
    echo "FAILURE: Color mismatch!\n";
}
