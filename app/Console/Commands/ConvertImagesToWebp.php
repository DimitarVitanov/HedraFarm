<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Slider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:convert-webp {--dry-run : Show what would be converted without actually converting}';
    protected $description = 'Convert all existing images to WebP format for better performance';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting image conversion to WebP...');
        
        $converted = 0;
        $failed = 0;
        $skipped = 0;

        // Convert Product main images
        $this->info("\n📦 Processing Product images...");
        $products = Product::whereNotNull('main_image')->get();
        foreach ($products as $product) {
            $result = $this->convertImage($product, 'main_image', $dryRun);
            if ($result === 'converted') $converted++;
            elseif ($result === 'failed') $failed++;
            else $skipped++;
        }

        // Convert Product Gallery images
        $this->info("\n🖼️ Processing Product Gallery images...");
        $galleries = ProductGallery::whereNotNull('image')->get();
        foreach ($galleries as $gallery) {
            $result = $this->convertImage($gallery, 'image', $dryRun);
            if ($result === 'converted') $converted++;
            elseif ($result === 'failed') $failed++;
            else $skipped++;
        }

        // Convert Blog images
        $this->info("\n📝 Processing Blog images...");
        $blogs = Blog::whereNotNull('image')->get();
        foreach ($blogs as $blog) {
            $result = $this->convertImage($blog, 'image', $dryRun);
            if ($result === 'converted') $converted++;
            elseif ($result === 'failed') $failed++;
            else $skipped++;
        }

        // Convert Slider images
        $this->info("\n🎠 Processing Slider images...");
        $sliders = Slider::whereNotNull('image')->get();
        foreach ($sliders as $slider) {
            $result = $this->convertImage($slider, 'image', $dryRun);
            if ($result === 'converted') $converted++;
            elseif ($result === 'failed') $failed++;
            else $skipped++;
        }

        $this->newLine();
        $this->info("✅ Conversion complete!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Converted', $converted],
                ['Skipped (already WebP or not found)', $skipped],
                ['Failed', $failed],
            ]
        );

        return Command::SUCCESS;
    }

    private function convertImage($model, $field, $dryRun = false): string
    {
        $imagePath = $model->$field;
        
        if (empty($imagePath)) {
            return 'skipped';
        }

        // Skip if already WebP
        if (str_ends_with(strtolower($imagePath), '.webp')) {
            $this->line("  ⏭️ Already WebP: {$imagePath}");
            return 'skipped';
        }

        // Get full path
        $fullPath = public_path($imagePath);
        
        if (!file_exists($fullPath)) {
            // Try storage path
            $fullPath = storage_path('app/public/' . $imagePath);
            if (!file_exists($fullPath)) {
                $this->warn("  ⚠️ File not found: {$imagePath}");
                return 'skipped';
            }
        }

        // Get file extension
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $this->line("  ⏭️ Unsupported format: {$imagePath}");
            return 'skipped';
        }

        if ($dryRun) {
            $this->line("  🔄 Would convert: {$imagePath}");
            return 'converted';
        }

        try {
            // Create WebP version
            $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $fullPath);
            $webpRelativePath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $imagePath);

            // Load image based on type
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($fullPath);
                    break;
                case 'png':
                    $image = imagecreatefrompng($fullPath);
                    // Preserve transparency
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($fullPath);
                    break;
                default:
                    return 'skipped';
            }

            if (!$image) {
                $this->error("  ❌ Failed to load: {$imagePath}");
                return 'failed';
            }

            // Convert to WebP (quality 80 is a good balance)
            $success = imagewebp($image, $webpPath, 80);
            imagedestroy($image);

            if ($success) {
                // Update database record
                $model->$field = $webpRelativePath;
                $model->save();
                
                $this->info("  ✅ Converted: {$imagePath} → {$webpRelativePath}");
                return 'converted';
            } else {
                $this->error("  ❌ Failed to convert: {$imagePath}");
                return 'failed';
            }

        } catch (\Exception $e) {
            $this->error("  ❌ Error converting {$imagePath}: " . $e->getMessage());
            return 'failed';
        }
    }
}
