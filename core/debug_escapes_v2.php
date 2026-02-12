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
    // Regex finds backslash not followed by valid JSON escape chars
    // Regex: ~\\(?![/\"\\bfnrtu])~
    // PHP String for Regex: '~\\\\(?![/"\\\\bfnrtu])~'
    if (preg_match_all('~\\\\(?![/"\\\\bfnrtu])~', $str, $matches, PREG_OFFSET_CAPTURE)) {
        echo "[MATCH] Potential issue in $context:\n";
        foreach ($matches[0] as $match) {
            $offset = $match[1]; // Offset in string
            echo "  Offset $offset. Char after: '".substr($str, $offset + 1, 1)."'\n";
            echo '  Context: '.substr($str, max(0, $offset - 10), 20)."\n";
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

echo "Done checking.\n";
