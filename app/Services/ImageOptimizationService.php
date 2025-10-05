<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class ImageOptimizationService
{
    protected $manager;

    public function __construct()
    {
        // Try GD first, fallback to Imagick if available
        try {
            if (extension_loaded('gd')) {
                $this->manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            } elseif (extension_loaded('imagick')) {
                $this->manager = new ImageManager(new \Intervention\Image\Drivers\Imagick\Driver());
            } else {
                throw new \Exception('Neither GD nor Imagick extension is available');
            }
        } catch (\Exception $e) {
            Log::error('Failed to initialize image manager: ' . $e->getMessage());
            // For now, we'll work without image processing
            $this->manager = null;
        }
    }

    /**
     * Optimize and upscale image
     */
    public function optimizeImage($imagePath, $options = [])
    {
        if (!$this->manager) {
            Log::warning('Image manager not available, skipping optimization');
            return $imagePath;
        }

        $defaultOptions = [
            'width' => 1600,
            'height' => 1200,
            'quality' => 95,
            'format' => 'webp',
            'upscale' => true,
            'maintain_aspect' => true
        ];

        $options = array_merge($defaultOptions, $options);

        try {
            $image = $this->manager->read($imagePath);
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Calculate new dimensions maintaining aspect ratio
            if ($options['maintain_aspect']) {
                $aspectRatio = $originalWidth / $originalHeight;
                
                if ($aspectRatio > 1) {
                    // Landscape
                    $newWidth = $options['width'];
                    $newHeight = $newWidth / $aspectRatio;
                    
                    if ($newHeight > $options['height']) {
                        $newHeight = $options['height'];
                        $newWidth = $newHeight * $aspectRatio;
                    }
                } else {
                    // Portrait or square
                    $newHeight = $options['height'];
                    $newWidth = $newHeight * $aspectRatio;
                    
                    if ($newWidth > $options['width']) {
                        $newWidth = $options['width'];
                        $newHeight = $newWidth / $aspectRatio;
                    }
                }
            } else {
                $newWidth = $options['width'];
                $newHeight = $options['height'];
            }

            // Upscale if enabled and image is smaller
            if ($options['upscale'] || ($originalWidth > $newWidth || $originalHeight > $newHeight)) {
                $image = $image->resize($newWidth, $newHeight);
            }

            // Apply sharpening for upscaled images
            if ($options['upscale'] && ($originalWidth < $newWidth || $originalHeight < $newHeight)) {
                $image = $image->sharpen(10);
            }

            // Save optimized image
            $pathInfo = pathinfo($imagePath);
            $optimizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_optimized.' . $options['format'];
            
            // Encode with quality settings
            $encoded = $image->encode($options['format'], $options['quality']);
            file_put_contents($optimizedPath, $encoded);

            return $optimizedPath;

        } catch (\Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
            return $imagePath; // Return original if optimization fails
        }
    }

    /**
     * Create multiple sizes for responsive images
     */
    public function createResponsiveSizes($imagePath)
    {
        $sizes = [
            'thumb' => ['width' => 300, 'height' => 225],
            'medium' => ['width' => 800, 'height' => 600],
            'large' => ['width' => 1600, 'height' => 1200]
        ];

        $responsiveImages = [];

        foreach ($sizes as $size => $dimensions) {
            $optimizedPath = $this->optimizeImage($imagePath, [
                'width' => $dimensions['width'],
                'height' => $dimensions['height'],
                'quality' => $size === 'thumb' ? 85 : 95,
                'format' => 'webp'
            ]);

            $responsiveImages[$size] = $optimizedPath;
        }

        return $responsiveImages;
    }

    /**
     * Auto-enhance image quality
     */
    public function enhanceImage($imagePath)
    {
        if (!$this->manager) {
            Log::warning('Image manager not available, skipping enhancement');
            return $imagePath;
        }

        try {
            $image = $this->manager->read($imagePath);
            
            // Auto-enhance
            $image = $image->brightness(5)    // Slight brightness increase
                          ->contrast(10)      // Enhance contrast
                          ->gamma(1.1);       // Adjust gamma
            
            // Save enhanced image
            $pathInfo = pathinfo($imagePath);
            $enhancedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_enhanced.' . $pathInfo['extension'];
            
            $image->save($enhancedPath);
            
            return $enhancedPath;

        } catch (\Exception $e) {
            Log::error('Image enhancement failed: ' . $e->getMessage());
            return $imagePath;
        }
    }
}
