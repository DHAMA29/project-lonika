<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\JenisBarang;
use App\Models\Barang;

try {
    echo "=== CATEGORY AND PRODUCT DEBUG ===\n\n";
    
    echo "Categories in database:\n";
    $categories = JenisBarang::withCount('barang')->get();
    foreach($categories as $cat) {
        echo "ID: {$cat->id} | Name: {$cat->nama} | Products: {$cat->barang_count}\n";
    }
    
    echo "\nProducts with categories (first 10):\n";
    $products = Barang::with('jenisBarang')->take(10)->get();
    foreach($products as $p) {
        echo "Product: {$p->nama} | Category ID: {$p->jenis_barang_id} | Category: {$p->jenisBarang->nama}\n";
    }
    
    echo "\nFirst 4 categories (shown by default):\n";
    $firstFour = JenisBarang::withCount('barang')->having('barang_count', '>', 0)->take(4)->get();
    foreach($firstFour as $cat) {
        echo "ID: {$cat->id} | Name: {$cat->nama}\n";
    }
    
    echo "\nMore categories (after first 4):\n";
    $allCategories = JenisBarang::withCount('barang')->having('barang_count', '>', 0)->get();
    $moreCategories = $allCategories->skip(4);
    foreach($moreCategories as $cat) {
        echo "ID: {$cat->id} | Name: {$cat->nama}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}