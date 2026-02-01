<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertStaticImagesToWebp extends Command
{
    protected $signature = 'images:convert-static {--dry-run : Show what would be converted without actually converting}';
    protected $description = 'Convert all static images in public/assets to WebP format';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting static image conversion to WebP...');
        
        $converted = 0;
        $failed = 0;
        $skipped = 0;

        $directories = [
            public_path('assets/img'),
            public_path('assets/slider'),
            public_path('assets/products'),
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                $this->warn("Directory not found: {$directory}");
                continue;
            }

            $this->info("\n📁 Processing: {$directory}");
            
            $files = File::allFiles($directory);
            
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    continue;
                }

                $fullPath = $file->getPathname();
                $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $fullPath);

                // Skip if WebP already exists
                if (file_exists($webpPath)) {
                    $this->line("  ⏭️ WebP exists: " . basename($fullPath));
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $this->line("  🔄 Would convert: " . basename($fullPath));
                    $converted++;
                    continue;
                }

                try {
                    $image = $this->createImageFromFile($fullPath, $extension);
                    
                    if (!$image) {
                        $this->error("  ❌ Failed to load: " . basename($fullPath));
                        $failed++;
                        continue;
                    }

                    $success = imagewebp($image, $webpPath, 80);
                    imagedestroy($image);

                    if ($success) {
                        $this->info("  ✅ Converted: " . basename($fullPath));
                        $converted++;
                    } else {
                        $this->error("  ❌ Failed: " . basename($fullPath));
                        $failed++;
                    }
                } catch (\Exception $e) {
                    $this->error("  ❌ Error: " . $e->getMessage());
                    $failed++;
                }
            }
        }

        $this->newLine();
        $this->info("✅ Conversion complete!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Converted', $converted],
                ['Skipped (WebP exists)', $skipped],
                ['Failed', $failed],
            ]
        );

        return Command::SUCCESS;
    }

    private function createImageFromFile(string $path, string $extension)
    {
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
