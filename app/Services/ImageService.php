<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Convert an uploaded image to WebP format
     */
    public static function convertToWebp(UploadedFile $file, string $directory, string $filename = null): string
    {
        $filename = $filename ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $filename . '.webp';
        
        $image = self::createImageFromFile($file->getPathname(), $file->getClientOriginalExtension());
        
        if (!$image) {
            // Fallback to original upload if conversion fails
            return $file->store($directory, 'public');
        }

        $path = $directory . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);
        
        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Save as WebP
        imagewebp($image, $fullPath, 80);
        imagedestroy($image);

        return $path;
    }

    /**
     * Convert an existing file to WebP
     */
    public static function convertExistingToWebp(string $sourcePath): ?string
    {
        if (!file_exists($sourcePath)) {
            return null;
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        
        if ($extension === 'webp') {
            return $sourcePath; // Already WebP
        }

        $image = self::createImageFromFile($sourcePath, $extension);
        
        if (!$image) {
            return null;
        }

        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $sourcePath);
        
        imagewebp($image, $webpPath, 80);
        imagedestroy($image);

        return $webpPath;
    }

    /**
     * Create GD image resource from file
     */
    private static function createImageFromFile(string $path, string $extension)
    {
        $extension = strtolower($extension);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($path);
            case 'png':
                $image = imagecreatefrompng($path);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                return $image;
            case 'gif':
                return imagecreatefromgif($path);
            default:
                return null;
        }
    }
}
