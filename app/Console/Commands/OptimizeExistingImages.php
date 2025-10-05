<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {--force : Force optimization even if optimized versions exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize existing product images with upscaling and quality enhancement';

    protected $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting image optimization process...');
        
        $barangs = Barang::whereNotNull('gambar')->get();
        $force = $this->option('force');
        
        if ($barangs->isEmpty()) {
            $this->warn('No products with images found.');
            return 0;
        }

        $this->info("Found {$barangs->count()} products with images.");
        
        $bar = $this->output->createProgressBar($barangs->count());
        $bar->start();

        $optimized = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($barangs as $barang) {
            try {
                $imagePath = Storage::disk('public')->path($barang->gambar);
                
                if (!file_exists($imagePath)) {
                    $this->warn("\nImage not found: {$barang->gambar}");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Check if optimized version already exists
                $pathInfo = pathinfo($imagePath);
                $optimizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_optimized.webp';
                
                if (!$force && file_exists($optimizedPath)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Optimize image
                $this->imageService->optimizeImage($imagePath, [
                    'width' => 1920,
                    'height' => 1440,
                    'quality' => 95,
                    'upscale' => true,
                    'format' => 'webp'
                ]);

                // Create responsive sizes
                $this->imageService->createResponsiveSizes($imagePath);

                // Enhance image quality
                $this->imageService->enhanceImage($imagePath);

                $optimized++;

            } catch (\Exception $e) {
                $this->error("\nFailed to optimize image for product: {$barang->nama}. Error: " . $e->getMessage());
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        
        $this->newLine(2);
        $this->info("✅ Optimization completed!");
        $this->info("📊 Summary:");
        $this->info("   - Optimized: {$optimized}");
        $this->info("   - Skipped: {$skipped}");
        $this->info("   - Errors: {$errors}");

        if ($optimized > 0) {
            $this->info("🎉 {$optimized} images have been optimized and upscaled!");
        }

        return 0;
    }
}
