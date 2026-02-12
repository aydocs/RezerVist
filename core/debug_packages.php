<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Package;

echo "--- PACKAGES TABLE ---\n";
foreach (Package::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Slug: {$p->slug} | Price: {$p->price_monthly}\n";
}
