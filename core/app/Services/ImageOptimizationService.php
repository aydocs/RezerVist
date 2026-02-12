<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageOptimizationService
{
    /**
     * Optimize and store an image.
     * Converts to WebP and creates multiple sizes.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $directory
     * @return array Paths of generated images
     */
    public static function process($file, $directory = 'photos')
    {
        $filename = Str::random(40);
        $fullPath = "$directory/$filename.webp";
        $thumbPath = "$directory/thumbnails/$filename.webp";

        // Ensure directories exist
        Storage::disk('public')->makeDirectory($directory);
        Storage::disk('public')->makeDirectory("$directory/thumbnails");

        // Initialize Manager with available driver
        $manager = null;
        if (extension_loaded('imagick')) {
            $manager = \Intervention\Image\ImageManager::imagick();
        } elseif (extension_loaded('gd')) {
            $manager = \Intervention\Image\ImageManager::gd();
        }

        // Fallback: If no driver, just store the raw file
        if (! $manager) {
            Storage::disk('public')->put($fullPath, file_get_contents($file->getRealPath()));

            return [
                'original' => $fullPath,
                'thumbnail' => $fullPath, // No thumbnail in fallback
            ];
        }

        // Main Image (Resized if too large)
        $img = $manager->read($file);

        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }

        $encoded = $img->toWebp(80);
        Storage::disk('public')->put($fullPath, (string) $encoded);

        // Thumbnail (For listings)
        $thumb = $manager->read($file)->cover(400, 300);
        $encodedThumb = $thumb->toWebp(70);
        Storage::disk('public')->put($thumbPath, (string) $encodedThumb);

        return [
            'original' => $fullPath,
            'thumbnail' => $thumbPath,
        ];
    }
}
