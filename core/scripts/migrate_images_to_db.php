<?php

use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting Image Migration to Database...\n";

// 1. Businesses (Logos)
echo "Migrating Business Logos...\n";
Business::whereNotNull('logo')->whereNull('image_blob')->chunk(100, function ($businesses) {
    foreach ($businesses as $business) {
        if (Storage::disk('public')->exists($business->logo)) {
            $content = Storage::disk('public')->get($business->logo);
            $business->update(['image_blob' => $content]);
            echo "Migrated Logo for Business ID: {$business->id}\n";
        }
    }
});

// 2. Business Images (Gallery)
echo "Migrating Business Gallery Images...\n";
BusinessImage::whereNotNull('image_path')->whereNull('image_blob')->chunk(100, function ($images) {
    foreach ($images as $image) {
        if (Storage::disk('public')->exists($image->image_path)) {
            $content = Storage::disk('public')->get($image->image_path);
            $image->update(['image_blob' => $content]);
            echo "Migrated Gallery Image ID: {$image->id}\n";
        }
    }
});

// 3. Menus (Products)
echo "Migrating Menu Item Images...\n";
Menu::whereNotNull('image')->whereNull('image_blob')->chunk(100, function ($menus) {
    foreach ($menus as $menu) {
        if (Storage::disk('public')->exists($menu->image)) {
            $content = Storage::disk('public')->get($menu->image);
            $menu->update(['image_blob' => $content]);
            echo "Migrated Menu Image ID: {$menu->id}\n";
        }
    }
});

// 4. Users (Profile Photos)
echo "Migrating User Profile Photos...\n";
User::whereNotNull('profile_photo_path')->whereNull('profile_photo_blob')->chunk(100, function ($users) {
    foreach ($users as $user) {
        if (Storage::disk('public')->exists($user->profile_photo_path)) {
            $content = Storage::disk('public')->get($user->profile_photo_path);
            $user->update(['profile_photo_blob' => $content]);
            echo "Migrated Profile Photo for User ID: {$user->id}\n";
        }
    }
});

echo "Migration Complete!\n";
