<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Services\StockAvailabilityService;
use Carbon\Carbon;

class TestStockDeduction extends Command
{
    protected $signature = 'test:stock-deduction';
    protected $description = 'Test stock deduction logic';

    public function handle()
    {
        $this->info('🧪 Testing Stock Deduction Logic...');
        $this->newLine();

        $stockService = app(StockAvailabilityService::class);
        $now = Carbon::now();

        $this->info("Current time: {$now}");
        $this->info("Current date: {$now->format('Y-m-d')}");
        $this->newLine();

        // Check all peminjaman with detailed date analysis
        $allPeminjaman = Peminjaman::where('stock_reserved', true)
            ->where('stock_deducted', false)
            ->take(10)
            ->get();

        $this->info("All reserved but not deducted peminjaman: {$allPeminjaman->count()}");

        foreach ($allPeminjaman as $peminjaman) {
            $this->line("ID: {$peminjaman->id}");
            $this->line("  Tanggal Pinjam: {$peminjaman->tanggal_pinjam}");
            $this->line("  Tanggal Pinjam (raw): " . $peminjaman->getRawOriginal('tanggal_pinjam'));
            $this->line("  Tanggal Pinjam Date: {$peminjaman->tanggal_pinjam->format('Y-m-d')}");
            $this->line("  Current Date: {$now->format('Y-m-d')}");
            $this->line("  Date comparison: " . ($peminjaman->tanggal_pinjam->format('Y-m-d') <= $now->format('Y-m-d') ? 'SHOULD DEDUCT' : 'FUTURE'));
            $this->line("  Direct comparison: " . ($peminjaman->tanggal_pinjam <= $now ? 'SHOULD DEDUCT' : 'FUTURE'));
            $this->newLine();
        }

        // Now check with different query approach
        $this->info('Checking with different query approaches:');
        
        // Method 1: whereDate
        $method1 = Peminjaman::where('stock_reserved', true)
            ->where('stock_deducted', false)
            ->whereDate('tanggal_pinjam', '<=', $now->format('Y-m-d'))
            ->count();
        $this->line("Method 1 (whereDate): {$method1} records");

        // Method 2: where with date format
        $method2 = Peminjaman::where('stock_reserved', true)
            ->where('stock_deducted', false)
            ->where('tanggal_pinjam', '<=', $now)
            ->count();
        $this->line("Method 2 (where <=): {$method2} records");

        // Method 3: raw date comparison
        $todayString = $now->format('Y-m-d');
        $method3 = Peminjaman::where('stock_reserved', true)
            ->where('stock_deducted', false)
            ->whereRaw("DATE(tanggal_pinjam) <= ?", [$todayString])
            ->count();
        $this->line("Method 3 (whereRaw): {$method3} records");

        $this->newLine();

        // Test processScheduledStockOperations
        $this->info('Testing processScheduledStockOperations...');
        try {
            $result = $stockService->processScheduledStockOperations();
            $this->line("Deductions: " . ($result['deductions_processed'] ?? 'N/A'));
            $this->line("Returns: " . ($result['returns_processed'] ?? 'N/A'));
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('✅ Test complete!');
    }
}