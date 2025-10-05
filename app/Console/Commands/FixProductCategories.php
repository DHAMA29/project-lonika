<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JenisBarang;
use App\Models\Barang;

class FixProductCategories extends Command
{
    protected $signature = 'fix:product-categories';
    protected $description = 'Fix products that might have wrong category assignments';

    public function handle()
    {
        $this->info('=== FIXING PRODUCT CATEGORIES ===');
        
        // Check for orphaned products
        $orphanedProducts = Barang::whereNotIn('jenis_barang_id', JenisBarang::pluck('id'))->get();
        
        if ($orphanedProducts->count() > 0) {
            $this->warn("Found {$orphanedProducts->count()} products with invalid categories");
            
            // Get first available category as default
            $defaultCategory = JenisBarang::first();
            
            if ($defaultCategory) {
                foreach ($orphanedProducts as $product) {
                    $this->line("Fixing product: {$product->nama} - Setting to category: {$defaultCategory->nama}");
                    $product->update(['jenis_barang_id' => $defaultCategory->id]);
                }
                $this->info("Fixed {$orphanedProducts->count()} products");
            } else {
                $this->error("No categories available to assign!");
            }
        } else {
            $this->info("All products have valid categories!");
        }
        
        // Show final statistics
        $this->info("\n=== FINAL STATISTICS ===");
        $categories = JenisBarang::withCount('barang')->get();
        
        foreach ($categories as $category) {
            $this->line("{$category->nama}: {$category->barang_count} products");
        }
        
        $this->info("\nAll done!");
    }
}