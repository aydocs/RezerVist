<?php

use App\Models\Business;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$business = Business::where('name', 'like', '%Deniz Mahsulleri%')->first();

if (! $business) {
    exit("Business not found.\n");
}

function checkInvalidEscapes($str, $context = '')
{
    // Regex to find single backslash not followed by valid escape char
    // valid: " \ / b f n r t u
    // matches: \x where x is not one of above.
    // Note: \\ in regex matches literal \
    if (preg_match_all('/\\\(?!["\\\/bfnrtu])/', $str, $matches, PREG_OFFSET_CAPTURE)) {
        echo "[ERROR] Invalid escape sequence in $context:\n";
        foreach ($matches[0] as $match) {
            $offset = $match[1];
            echo "  Offset $offset: ".substr($str, max(0, $offset - 10), 20)."\n";
        }
    }
}

// Check Business Fields (Raw attributes)
$attrs = $business->getAttributes();
foreach ($attrs as $key => $val) {
    if (is_string($val)) {
        checkInvalidEscapes($val, "Business Field: $key");
    }
}

// Check Menus
$menus = \App\Models\Menu::where('business_id', $business->id)->get();
foreach ($menus as $menu) {
    $mAttrs = $menu->getAttributes();
    foreach ($mAttrs as $key => $val) {
        if (is_string($val)) {
            checkInvalidEscapes($val, "Menu {$menu->id} Field: $key");
        }
    }
}

// Check Reviews
$reviews = \App\Models\Review::where('business_id', $business->id)->get();
foreach ($reviews as $review) {
    if (is_string($review->comment)) {
        checkInvalidEscapes($review->comment, "Review {$review->id} Comment");
    }
}

echo "Done checking.\n";
