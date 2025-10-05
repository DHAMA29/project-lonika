<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Services\StockAvailabilityService;
use Carbon\Carbon;

class DebugStockIssue extends Command
{
    protected $signature = 'debug:stock-issue';
    protected $description = 'Debug stock management issue';

    public function handle()
    {
        $this->info('🔍 Debugging Stock Management Issue...');
        $this->newLine();

        // 1. Check recent peminjaman data
        $this->info('📋 Recent Peminjaman Data:');
        $recentPeminjaman = Peminjaman::with('detail.barang')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        foreach ($recentPeminjaman as $peminjaman) {
            $this->line("ID: {$peminjaman->id} | Status: {$peminjaman->status} | Tanggal: {$peminjaman->tanggal_pinjam}");
            $this->line("  Stock Reserved: " . ($peminjaman->stock_reserved ? '✅' : '❌'));
            $this->line("  Stock Deducted: " . ($peminjaman->stock_deducted ? '✅' : '❌'));
            $this->line("  Stock Returned: " . ($peminjaman->stock_returned ? '✅' : '❌'));
            if ($peminjaman->stock_deduction_date) {
                $this->line("  Deduction Date: {$peminjaman->stock_deduction_date}");
            }
            $this->newLine();
        }

        // 2. Check active peminjaman that should have stock deducted
        $this->info('🎯 Active Peminjaman Analysis:');
        $today = Carbon::now();
        
        $activePeminjaman = Peminjaman::where('status', 'dipinjam')
            ->orWhere(function($query) use ($today) {
                $query->where('tanggal_pinjam', '<=', $today->toDateString())
                      ->where('tanggal_kembali', '>=', $today->toDateString());
            })
            ->get();

        $this->line("Active peminjaman count: " . $activePeminjaman->count());

        foreach ($activePeminjaman as $peminjaman) {
            $this->line("ID: {$peminjaman->id} | Status: {$peminjaman->status}");
            $this->line("  Period: {$peminjaman->tanggal_pinjam} to {$peminjaman->tanggal_kembali}");
            $this->line("  Should be active today: " . ($peminjaman->tanggal_pinjam <= $today->toDateString() && $peminjaman->tanggal_kembali >= $today->toDateString() ? '✅' : '❌'));
            $this->line("  Stock Deducted: " . ($peminjaman->stock_deducted ? '✅' : '❌'));
            
            if (!$peminjaman->stock_deducted && $peminjaman->tanggal_pinjam <= $today->toDateString()) {
                $this->warn("  ⚠️ Should have stock deducted but hasn't!");
            }
            $this->newLine();
        }

        // 3. Check actual stock levels
        $this->info('📦 Current Stock Levels:');
        $barangWithStock = Barang::where('stok', '>', 0)->take(5)->get();
        
        foreach ($barangWithStock as $barang) {
            $this->line("Barang ID: {$barang->id} | {$barang->nama} | Stock: {$barang->stok}");
        }
        $this->newLine();

        // 4. Test StockAvailabilityService for a specific item
        $this->info('🧪 Testing StockAvailabilityService:');
        $testBarang = Barang::first();
        if ($testBarang) {
            $stockService = app(StockAvailabilityService::class);
            
            $availability = $stockService->checkAvailability(
                $testBarang->id,
                $today,
                $today->copy()->addDay()
            );
            
            $this->line("Test Item: {$testBarang->nama} (ID: {$testBarang->id})");
            $this->line("Available: " . ($availability['available'] ? '✅' : '❌'));
            $this->line("Available Quantity: {$availability['available_quantity']}");
            $this->line("Database Stock: {$testBarang->stok}");
            
            if (isset($availability['conflicts']) && count($availability['conflicts']) > 0) {
                $this->line("Conflicts found: " . count($availability['conflicts']));
                foreach ($availability['conflicts'] as $conflict) {
                    $this->line("  - Peminjaman ID: {$conflict['peminjaman_id']} | Quantity: {$conflict['quantity']} | Deducted: " . ($conflict['stock_deducted'] ? '✅' : '❌'));
                }
            }
        }
        $this->newLine();

        // 5. Test scheduled operations
        $this->info('⚙️ Testing Scheduled Operations:');
        try {
            $result = $stockService->processScheduledStockOperations();
            $this->line("Deductions processed: " . ($result['deductions_processed'] ?? 'N/A'));
            $this->line("Returns processed: " . ($result['returns_processed'] ?? 'N/A'));
        } catch (\Exception $e) {
            $this->error("Error in scheduled operations: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('✅ Debug analysis complete!');
    }
}