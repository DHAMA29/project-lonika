<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Services\StockAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MigrateStockSystem extends Command
{
    protected $signature = 'migrate:stock-system {--dry-run : Show what would be migrated without making changes}';
    protected $description = 'Migrate old stock system to new stock management system';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('🚀 Migrating to New Stock Management System...');
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }
        $this->newLine();

        $now = Carbon::now();

        // Strategy: Assume old system already handled stock correctly
        // We just need to mark the stock management flags properly

        // 1. Handle completed rentals
        $this->info('📋 Step 1: Handling completed rentals...');
        $completedRentals = Peminjaman::where('status', 'selesai')
            ->where('stock_reserved', false)
            ->get();

        $this->line("Found {$completedRentals->count()} completed rentals to migrate");

        foreach ($completedRentals as $rental) {
            $this->line("  Rental ID {$rental->id}: {$rental->tanggal_pinjam} to {$rental->tanggal_kembali}");
            
            if (!$isDryRun) {
                // For completed rentals, assume stock was deducted and returned in old system
                $rental->update([
                    'stock_reserved' => true,
                    'stock_deducted' => true,
                    'stock_returned' => true,
                    'stock_deduction_date' => $rental->tanggal_pinjam,
                    'stock_return_date' => $rental->tanggal_kembali,
                ]);
                $this->line("    ✓ Marked as: reserved → deducted → returned");
            } else {
                $this->line("    Would mark as: reserved → deducted → returned");
            }
        }

        $this->newLine();

        // 2. Handle active rentals (currently in use)
        $this->info('📋 Step 2: Handling active rentals...');
        $activeRentals = Peminjaman::where('status', 'belum dikembalikan')
            ->whereDate('tanggal_pinjam', '<=', $now->format('Y-m-d'))
            ->where('stock_reserved', false)
            ->get();

        $this->line("Found {$activeRentals->count()} active rentals to migrate");

        foreach ($activeRentals as $rental) {
            $this->line("  Rental ID {$rental->id}: {$rental->tanggal_pinjam} to {$rental->tanggal_kembali}");
            
            if (!$isDryRun) {
                // For active rentals, assume stock was deducted in old system but not returned yet
                $rental->update([
                    'stock_reserved' => true,
                    'stock_deducted' => true,
                    'stock_returned' => false,
                    'stock_deduction_date' => $rental->tanggal_pinjam,
                    'stock_return_date' => null,
                ]);
                $this->line("    ✓ Marked as: reserved → deducted (waiting for return)");
            } else {
                $this->line("    Would mark as: reserved → deducted (waiting for return)");
            }
        }

        $this->newLine();

        // 3. Handle future rentals
        $this->info('📋 Step 3: Handling future rentals...');
        $futureRentals = Peminjaman::where('status', 'belum dikembalikan')
            ->whereDate('tanggal_pinjam', '>', $now->format('Y-m-d'))
            ->where('stock_reserved', false)
            ->get();

        $this->line("Found {$futureRentals->count()} future rentals to migrate");

        foreach ($futureRentals as $rental) {
            $this->line("  Rental ID {$rental->id}: {$rental->tanggal_pinjam} to {$rental->tanggal_kembali}");
            
            if (!$isDryRun) {
                // For future rentals, just reserve the stock
                $rental->update([
                    'stock_reserved' => true,
                    'stock_deducted' => false,
                    'stock_returned' => false,
                    'stock_deduction_date' => null,
                    'stock_return_date' => null,
                ]);
                $this->line("    ✓ Marked as: reserved (will deduct on rental date)");
            } else {
                $this->line("    Would mark as: reserved (will deduct on rental date)");
            }
        }

        $this->newLine();

        // 4. Verify the new system works
        $this->info('📋 Step 4: Testing new system...');
        
        if (!$isDryRun) {
            // Test with a sample availability check
            $testBarang = Barang::first();
            if ($testBarang) {
                $stockService = app(StockAvailabilityService::class);
                $availability = $stockService->checkAvailability(
                    $testBarang->id,
                    $now,
                    $now->copy()->addDay()
                );
                
                $this->line("Sample availability check for {$testBarang->nama}:");
                $this->line("  Available: " . ($availability['available'] ? 'YES' : 'NO'));
                $this->line("  Available quantity: {$availability['available_quantity']}");
                $this->line("  Database stock: {$testBarang->stok}");
            }

            // Test scheduled operations
            $result = $stockService->processScheduledStockOperations();
            $this->line("Scheduled operations test:");
            $this->line("  Deductions processed: " . ($result['deductions_processed'] ?? 0));
            $this->line("  Returns processed: " . ($result['returns_processed'] ?? 0));
        }

        $this->newLine();
        
        // Summary
        $totalMigrated = $completedRentals->count() + $activeRentals->count() + $futureRentals->count();
        $this->info("📊 Migration Summary:");
        $this->line("  Completed rentals: {$completedRentals->count()}");
        $this->line("  Active rentals: {$activeRentals->count()}");
        $this->line("  Future rentals: {$futureRentals->count()}");
        $this->line("  Total migrated: {$totalMigrated}");
        
        if ($isDryRun) {
            $this->newLine();
            $this->warn("This was a DRY RUN. To apply migration, run without --dry-run flag:");
            $this->line("php artisan migrate:stock-system");
        } else {
            $this->newLine();
            $this->info('🎉 Migration complete! New stock management system is now active.');
            $this->line('Schedule the following command to run hourly for automatic stock operations:');
            $this->line('php artisan rental:manage-stock');
        }

        $this->newLine();
        $this->info('✅ Migration operation complete!');
    }
}