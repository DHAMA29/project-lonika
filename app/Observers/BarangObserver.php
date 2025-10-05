<?php

namespace App\Observers;

use App\Models\Barang;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BarangObserver
{
    protected $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle the Barang "created" event.
     */
    public function created(Barang $barang): void
    {
        // Image optimization will be handled by Filament's built-in processing
        Log::info("Barang created with ID: {$barang->id}");
    }

    /**
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        // Check if image was changed
        if ($barang->wasChanged('gambar') && $barang->gambar) {
            Log::info("Image updated for Barang ID: {$barang->id}");
        }
    }

    /**
     * Handle the Barang "deleted" event.
     */
    public function deleted(Barang $barang): void
    {
        // Delete associated image files
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
            
            // Delete optimized versions if they exist
            $this->deleteOptimizedVersions($barang->gambar);
        }
    }

    /**
     * Handle the Barang "force deleted" event.
     */
    public function forceDeleted(Barang $barang): void
    {
        $this->deleted($barang);
    }

    /**
     * Optimize uploaded image
     */
    protected function optimizeImage(Barang $barang): void
    {
        if (!$barang->gambar) {
            return;
        }

        try {
            $imagePath = Storage::disk('public')->path($barang->gambar);
            
            if (file_exists($imagePath)) {
                // Create optimized version
                $optimizedPath = $this->imageService->optimizeImage($imagePath, [
                    'width' => 1920,
                    'height' => 1440,
                    'quality' => 95,
                    'upscale' => true
                ]);

                // Create responsive sizes
                $this->imageService->createResponsiveSizes($imagePath);
                
                Log::info("Image optimized for Barang ID: {$barang->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to optimize image for Barang ID: {$barang->id}. Error: " . $e->getMessage());
        }
    }

    /**
     * Delete optimized image versions
     */
    protected function deleteOptimizedVersions(string $imagePath): void
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];

        // Delete optimized versions
        $optimizedPatterns = [
            $filename . '_optimized.webp',
            $filename . '_enhanced.' . $pathInfo['extension'],
            $filename . '_thumb.webp',
            $filename . '_medium.webp',
            $filename . '_large.webp'
        ];

        foreach ($optimizedPatterns as $pattern) {
            $filePath = $directory . '/' . $pattern;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }
}
