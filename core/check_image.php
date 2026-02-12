<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Menu;

$menu = Menu::whereNotNull('image')->latest()->first();
if ($menu) {
    echo "Image Path: " . $menu->image . "\n";
    echo "Full URL: " . asset('storage/' . $menu->image) . "\n";
} else {
    echo "No image found.\n";
}
