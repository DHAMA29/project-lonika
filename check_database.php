<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\JenisBarang;
use App\Models\Barang;

try {
    echo "=== DATABASE ANALYSIS ===\n";
    
    echo "\n1. Total JenisBarang: " . JenisBarang::count();
    echo "\n2. Total Barang: " . Barang::count();
    
    echo "\n\n=== JENIS BARANG LIST ===\n";
    $jenisBarang = JenisBarang::withCount('barang')->orderBy('id')->get();
    
    foreach($jenisBarang as $jenis) {
        echo "ID: {$jenis->id} | Nama: {$jenis->nama} | Jumlah Barang: {$jenis->barang_count}\n";
    }
    
    echo "\n\n=== BARANG DENGAN JENIS TIDAK VALID ===\n";
    $invalidBarang = Barang::whereNotIn('jenis_barang_id', JenisBarang::pluck('id'))->get();
    
    if($invalidBarang->count() > 0) {
        echo "Ditemukan {$invalidBarang->count()} barang dengan jenis_barang_id tidak valid:\n";
        foreach($invalidBarang as $barang) {
            echo "- ID: {$barang->id} | Nama: {$barang->nama} | jenis_barang_id: {$barang->jenis_barang_id}\n";
        }
    } else {
        echo "Semua barang memiliki jenis_barang_id yang valid.\n";
    }
    
    echo "\n\n=== BARANG TANPA JENIS ===\n";
    $barangTanpaJenis = Barang::whereNull('jenis_barang_id')->get();
    
    if($barangTanpaJenis->count() > 0) {
        echo "Ditemukan {$barangTanpaJenis->count()} barang tanpa jenis_barang_id:\n";
        foreach($barangTanpaJenis as $barang) {
            echo "- ID: {$barang->id} | Nama: {$barang->nama}\n";
        }
    } else {
        echo "Semua barang memiliki jenis_barang_id.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}