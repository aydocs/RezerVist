<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToDb extends Command
{
    protected $signature = 'images:migrate';
    protected $description = 'Migrate image files from storage to database LONGBLOB columns';

    public function handle()
    {
        $this->info('Starting Image Migration to Database...');

        // 1. Businesses (Logos)
        $this->info('Migrating Business Logos...');
        Business::whereNotNull('logo')->whereNull('image_blob')->chunk(50, function ($businesses) {
            foreach ($businesses as $business) {
                if (Storage::disk('public')->exists($business->logo)) {
                    $content = Storage::disk('public')->get($business->logo);
                    $business->update(['image_blob' => $content]);
                    $this->line("Migrated Logo for Business ID: {$business->id}");
                }
            }
        });

        // 2. Business Images (Gallery)
        $this->info('Migrating Business Gallery Images...');
        BusinessImage::whereNotNull('image_path')->whereNull('image_blob')->chunk(50, function ($images) {
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    $content = Storage::disk('public')->get($image->image_path);
                    $image->update(['image_blob' => $content]);
                    $this->line("Migrated Gallery Image ID: {$image->id}");
                }
            }
        });

        // 3. Menus (Products)
        $this->info('Migrating Menu Item Images...');
        Menu::whereNotNull('image')->whereNull('image_blob')->chunk(50, function ($menus) {
            foreach ($menus as $menu) {
                if (Storage::disk('public')->exists($menu->image)) {
                    $content = Storage::disk('public')->get($menu->image);
                    $menu->update(['image_blob' => $content]);
                    $this->line("Migrated Menu Image ID: {$menu->id}");
                }
            }
        });

        // 4. Users (Profile Photos)
        $this->info('Migrating User Profile Photos...');
        User::whereNotNull('profile_photo_path')->whereNull('profile_photo_blob')->chunk(50, function ($users) {
            foreach ($users as $user) {
                if (Storage::disk('public')->exists($user->profile_photo_path)) {
                    $content = Storage::disk('public')->get($user->profile_photo_path);
                    $user->update(['profile_photo_blob' => $content]);
                    $this->line("Migrated Profile Photo for User ID: {$user->id}");
                }
            }
        });

        $this->info('Migration Complete!');
    }
}
