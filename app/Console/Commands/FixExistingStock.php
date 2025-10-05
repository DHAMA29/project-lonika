<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Services\StockAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixExistingStock extends Command
{
    protected $signature = 'fix:existing-stock {--dry-run : Show what would be fixed without making changes}';
    protected $description = 'Fix existing peminjaman stock management';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('🔧 Fixing Existing Stock Management...');
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }
        $this->newLine();

        $stockService = app(StockAvailabilityService::class);
        $now = Carbon::now();

        // Find all peminjaman that should have stock management but don't
        $problemPeminjaman = Peminjaman::where(function($query) use ($now) {
                // Active rentals without proper stock management
                $query->where('status', 'belum dikembalikan')
                      ->whereDate('tanggal_pinjam', '<=', $now->format('Y-m-d'))
                      ->where('stock_deducted', false);
            })
            ->orWhere(function($query) {
                // Completed rentals that should have been managed
                $query->where('status', 'selesai')
                      ->where('stock_deducted', false);
            })
            ->with('detail.barang')
            ->get();

        $this->info("Found {$problemPeminjaman->count()} peminjaman that need stock management fixes");
        $this->newLine();

        $fixed = 0;
        $errors = 0;

        foreach ($problemPeminjaman as $peminjaman) {
            $this->line("Processing Peminjaman ID: {$peminjaman->id}");
            $this->line("  Status: {$peminjaman->status}");
            $this->line("  Period: {$peminjaman->tanggal_pinjam} to {$peminjaman->tanggal_kembali}");
            
            try {
                if (!$isDryRun) {
                    DB::beginTransaction();
                }

                // Step 1: Reserve stock if not already reserved
                if (!$peminjaman->stock_reserved) {
                    $this->line("  ✓ Setting stock_reserved = true");
                    if (!$isDryRun) {
                        $peminjaman->update(['stock_reserved' => true]);
                    }
                }

                // Step 2: For active or past rentals, deduct stock
                $shouldDeduct = $peminjaman->tanggal_pinjam <= $now;
                if ($shouldDeduct && !$peminjaman->stock_deducted) {
                    $this->line("  ✓ Deducting stock (rental started)");
                    
                    // Check if we have enough stock
                    $canDeduct = true;
                    foreach ($peminjaman->detail as $detail) {
                        if ($detail->barang->stok < $detail->jumlah) {
                            $this->warn("    ⚠️ Insufficient stock for {$detail->barang->nama} (needed: {$detail->jumlah}, available: {$detail->barang->stok})");
                            $canDeduct = false;
                        }
                    }

                    if ($canDeduct) {
                        if (!$isDryRun) {
                            // Deduct stock
                            foreach ($peminjaman->detail as $detail) {
                                $detail->barang->decrement('stok', $detail->jumlah);
                                $this->line("    - Deducted {$detail->jumlah} from {$detail->barang->nama}");
                            }

                            // Mark as deducted
                            $peminjaman->update([
                                'stock_deducted' => true,
                                'stock_deduction_date' => $peminjaman->tanggal_pinjam,
                            ]);
                        } else {
                            foreach ($peminjaman->detail as $detail) {
                                $this->line("    - Would deduct {$detail->jumlah} from {$detail->barang->nama}");
                            }
                        }
                    } else {
                        $this->error("    ❌ Cannot deduct - insufficient stock");
                        $errors++;
                        continue;
                    }
                }

                // Step 3: For completed rentals, return stock if it was deducted
                if ($peminjaman->status === 'selesai' && $peminjaman->stock_deducted && !$peminjaman->stock_returned) {
                    $this->line("  ✓ Returning stock (rental completed)");
                    
                    if (!$isDryRun) {
                        // Return stock
                        foreach ($peminjaman->detail as $detail) {
                            $detail->barang->increment('stok', $detail->jumlah);
                            $this->line("    + Returned {$detail->jumlah} to {$detail->barang->nama}");
                        }

                        // Mark as returned
                        $peminjaman->update([
                            'stock_returned' => true,
                            'stock_return_date' => $peminjaman->tanggal_kembali,
                        ]);
                    } else {
                        foreach ($peminjaman->detail as $detail) {
                            $this->line("    + Would return {$detail->jumlah} to {$detail->barang->nama}");
                        }
                    }
                }

                if (!$isDryRun) {
                    DB::commit();
                }
                
                $fixed++;
                $this->info("  ✅ Fixed successfully");

            } catch (\Exception $e) {
                if (!$isDryRun) {
                    DB::rollback();
                }
                $this->error("  ❌ Error: " . $e->getMessage());
                $errors++;
            }

            $this->newLine();
        }

        $this->newLine();
        $this->info("Summary:");
        $this->line("  Fixed: {$fixed}");
        $this->line("  Errors: {$errors}");
        
        if ($isDryRun) {
            $this->newLine();
            $this->warn("This was a DRY RUN. To apply fixes, run without --dry-run flag:");
            $this->line("php artisan fix:existing-stock");
        }

        $this->newLine();
        $this->info('✅ Fix operation complete!');
    }
}