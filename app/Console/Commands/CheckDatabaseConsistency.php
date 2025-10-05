<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JenisBarang;
use App\Models\Barang;

class CheckDatabaseConsistency extends Command
{
    protected $signature = 'check:database';
    protected $description = 'Check database consistency between JenisBarang and Barang';

    public function handle()
    {
        $this->info('=== DATABASE ANALYSIS ===');
        
        $this->info("\n1. Total JenisBarang: " . JenisBarang::count());
        $this->info("2. Total Barang: " . Barang::count());
        
        $this->info("\n=== JENIS BARANG LIST ===");
        $jenisBarang = JenisBarang::withCount('barang')->orderBy('id')->get();
        
        foreach($jenisBarang as $jenis) {
            $this->line("ID: {$jenis->id} | Nama: {$jenis->nama} | Jumlah Barang: {$jenis->barang_count}");
        }
        
        $this->info("\n=== BARANG DENGAN JENIS TIDAK VALID ===");
        $invalidBarang = Barang::whereNotIn('jenis_barang_id', JenisBarang::pluck('id'))->get();
        
        if($invalidBarang->count() > 0) {
            $this->warn("Ditemukan {$invalidBarang->count()} barang dengan jenis_barang_id tidak valid:");
            foreach($invalidBarang as $barang) {
                $this->line("- ID: {$barang->id} | Nama: {$barang->nama} | jenis_barang_id: {$barang->jenis_barang_id}");
            }
        } else {
            $this->info("Semua barang memiliki jenis_barang_id yang valid.");
        }
        
        $this->info("\n=== BARANG TANPA JENIS ===");
        $barangTanpaJenis = Barang::whereNull('jenis_barang_id')->get();
        
        if($barangTanpaJenis->count() > 0) {
            $this->warn("Ditemukan {$barangTanpaJenis->count()} barang tanpa jenis_barang_id:");
            foreach($barangTanpaJenis as $barang) {
                $this->line("- ID: {$barang->id} | Nama: {$barang->nama}");
            }
        } else {
            $this->info("Semua barang memiliki jenis_barang_id.");
        }
    }
}