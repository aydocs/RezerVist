<?php

namespace App\Services;

use App\Models\Business;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OGImageService
{
    public static function generateForBusiness(Business $business)
    {
        $manager = new ImageManager(new Driver);

        // Base canvas (1200x630 for FB/Twitter)
        $image = $manager->create(1200, 630)->fill('#6200EE');

        // Add a subtle gradient/pattern overlay if possible, or just a solid color with some design
        // For simplicity, we'll use a solid brand color and text

        // Add Business Name
        $image->text($business->name, 600, 300, function ($font) {
            $font->file(public_path('fonts/Outfit-Bold.ttf')); // Assuming this exists or using fallback
            $font->size(80);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add Rating
        $ratingText = 'Puan: '.($business->rating ?: 'N/A').' ⭐';
        $image->text($ratingText, 600, 400, function ($font) {
            $font->file(public_path('fonts/Outfit-Medium.ttf'));
            $font->size(40);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add Website Name
        $image->text('Rezervist.com', 600, 550, function ($font) {
            $font->file(public_path('fonts/Outfit-Light.ttf'));
            $font->size(30);
            $font->color('rgba(255, 255, 255, 0.6)');
            $font->align('center');
            $font->valign('middle');
        });

        return $image->toJpeg(80);
    }
}
