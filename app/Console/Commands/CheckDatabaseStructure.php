<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\JenisBarang;
use App\Models\Barang;

class CheckDatabaseStructure extends Command
{
    protected $signature = 'check:structure';
    protected $description = 'Check database table structures';

    public function handle()
    {
        $this->info('=== DATABASE STRUCTURE CHECK ===');
        
        // Check jenis_barang table
        $this->info("\n1. JENIS_BARANG TABLE:");
        $jenisColumns = Schema::getColumnListing('jenis_barang');
        foreach ($jenisColumns as $column) {
            $this->line("  - {$column}");
        }
        
        // Check barang table
        $this->info("\n2. BARANG TABLE:");
        $barangColumns = Schema::getColumnListing('barang');
        foreach ($barangColumns as $column) {
            $this->line("  - {$column}");
        }
        
        // Check indexes
        $this->info("\n3. FOREIGN KEY CHECK:");
        $barangWithoutCategory = Barang::whereNull('jenis_barang_id')->count();
        $this->line("  - Barang without category: {$barangWithoutCategory}");
        
        $invalidCategory = Barang::whereNotIn('jenis_barang_id', JenisBarang::pluck('id'))->count();
        $this->line("  - Barang with invalid category: {$invalidCategory}");
        
        // Show current data
        $this->info("\n4. CURRENT DATA:");
        $jenisBarang = JenisBarang::withCount('barang')->get();
        foreach ($jenisBarang as $jenis) {
            $this->line("  - {$jenis->nama} (ID: {$jenis->id}): {$jenis->barang_count} products");
        }
        
        $this->info("\nDatabase structure check completed!");
    }
}