<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Barang;
use App\Models\JenisBarang;

echo "=== DATA BARANG LONIKA ===\n\n";
echo "Total Barang: " . Barang::count() . "\n\n";

$jenisBarang = JenisBarang::with('barangs')->get();

foreach ($jenisBarang as $jenis) {
    echo "=== {$jenis->nama} ===\n";
    foreach ($jenis->barangs as $barang) {
        echo "- {$barang->nama} (Stok: {$barang->stok}, Rp " . number_format($barang->harga_hari, 0, ',', '.') . "/hari)\n";
    }
    echo "\n";
}

echo "Database seeder berhasil dijalankan!\n";