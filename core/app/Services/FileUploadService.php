<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FileUploadService
{
    /**
     * Upload and optimize an image.
     * Converts to WebP and optionally creates thumbnails.
     *
     * @return array ['original' => string, 'thumbnail' => string|null]
     */
    public static function uploadImage(UploadedFile $file, string $directory, bool $createThumbnail = false): array
    {
        $filename = Str::random(40);
        $fullPath = "$directory/$filename.webp";
        $thumbPath = $createThumbnail ? "$directory/thumbnails/$filename.webp" : null;

        // Ensure directories exist
        Storage::disk('public')->makeDirectory($directory);
        if ($createThumbnail) {
            Storage::disk('public')->makeDirectory("$directory/thumbnails");
        }

        // Check if any image driver is available
        $hasImagick = extension_loaded('imagick');
        $hasGd = extension_loaded('gd');

        if (! $hasImagick && ! $hasGd) {
            // Fallback: Just store the file as is without optimization
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $fullPath = "$directory/$filename.$extension";

            Storage::disk('public')->putFileAs($directory, $file, "$filename.$extension");

            return [
                'original' => $fullPath,
                'thumbnail' => null,
            ];
        }

        // Initialize Manager
        $manager = $hasImagick ? ImageManager::imagick() : ImageManager::gd();

        // Main Image (Resized if too large)
        $img = $manager->read($file);

        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }

        $encoded = $img->toWebp(80);
        Storage::disk('public')->put($fullPath, (string) $encoded);

        // Thumbnail
        if ($createThumbnail) {
            $thumb = $manager->read($file)->cover(400, 300);
            $encodedThumb = $thumb->toWebp(70);
            Storage::disk('public')->put($thumbPath, (string) $encodedThumb);
        }

        return [
            'original' => $fullPath,
            'thumbnail' => $thumbPath,
        ];
    }

    /**
     * Securely upload a document.
     */
    public static function uploadDocument(UploadedFile $file, string $directory): string
    {
        // Whitelist extensions for documents
        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowedExtensions)) {
            throw new \Exception('Geçersiz dosya formatı.');
        }

        // Basic mimetype check
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
        ];

        if (! in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Dosya içeriği güvenli görünmüyor.');
        }

        $filename = Str::uuid().'.'.$extension;
        $path = $directory.'/'.$filename;

        Storage::disk('public')->putFileAs($directory, $file, $filename);

        return $path;
    }

    /**
     * Delete a file from storage.
     */
    public static function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            // Also try to delete thumbnail if it's an image path
            $thumbPath = str_replace(['/', '.webp'], ['/thumbnails/', '.webp'], $path);
            if ($thumbPath !== $path && Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
        }
    }
}
