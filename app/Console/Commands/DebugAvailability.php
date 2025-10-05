<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Services\StockAvailabilityService;
use Carbon\Carbon;

class DebugAvailability extends Command
{
    protected $signature = 'debug:availability {barang_id=1}';
    protected $description = 'Debug availability calculation for specific item';

    public function handle()
    {
        $barangId = $this->argument('barang_id');
        
        $this->info("🔍 Debugging Availability for Barang ID: {$barangId}");
        $this->newLine();

        $barang = Barang::find($barangId);
        if (!$barang) {
            $this->error("Barang not found!");
            return;
        }

        $this->line("Barang: {$barang->nama}");
        $this->line("Database Stock: {$barang->stok}");
        $this->newLine();

        $stockService = app(StockAvailabilityService::class);
        $today = Carbon::now();
        $tomorrow = $today->copy()->addDay();

        // Test availability for today
        $this->info("Testing availability for today ({$today->format('Y-m-d')}):");
        $availability = $stockService->checkAvailability($barangId, $today, $today);
        
        $this->line("Available: " . ($availability['available'] ? 'YES' : 'NO'));
        $this->line("Available Quantity: {$availability['available_quantity']}");
        $this->line("Total Stock: {$availability['total_stock']}");
        
        if (isset($availability['conflicts']) && count($availability['conflicts']) > 0) {
            $this->line("Conflicts found: " . count($availability['conflicts']));
            foreach ($availability['conflicts'] as $conflict) {
                $this->line("  - Peminjaman ID: {$conflict['peminjaman_id']}");
                $this->line("    Quantity: {$conflict['quantity']}");
                $this->line("    Period: {$conflict['start_date']} to {$conflict['end_date']}");
                $this->line("    Status: {$conflict['status']}");
                $this->line("    Stock Deducted: " . ($conflict['stock_deducted'] ? 'YES' : 'NO'));
                $this->line("    Stock Returned: " . ($conflict['stock_returned'] ? 'YES' : 'NO'));
                $this->newLine();
            }
        } else {
            $this->line("No conflicts found");
        }

        $this->newLine();

        // Show all peminjaman affecting this barang
        $this->info("All peminjaman for this barang:");
        $allPeminjaman = Peminjaman::with('detail')
            ->whereHas('detail', function($query) use ($barangId) {
                $query->where('barang_id', $barangId);
            })
            ->orderBy('tanggal_pinjam', 'desc')
            ->take(10)
            ->get();

        foreach ($allPeminjaman as $peminjaman) {
            $detail = $peminjaman->detail->where('barang_id', $barangId)->first();
            $quantity = $detail ? $detail->jumlah : 0;
            
            $this->line("Peminjaman ID: {$peminjaman->id}");
            $this->line("  Period: {$peminjaman->tanggal_pinjam} to {$peminjaman->tanggal_kembali}");
            $this->line("  Status: {$peminjaman->status}");
            $this->line("  Quantity: {$quantity}");
            $this->line("  Reserved: " . ($peminjaman->stock_reserved ? 'YES' : 'NO'));
            $this->line("  Deducted: " . ($peminjaman->stock_deducted ? 'YES' : 'NO'));
            $this->line("  Returned: " . ($peminjaman->stock_returned ? 'YES' : 'NO'));
            $this->line("  Overlaps today: " . ($peminjaman->tanggal_pinjam <= $today && $peminjaman->tanggal_kembali >= $today ? 'YES' : 'NO'));
            $this->newLine();
        }

        $this->info('✅ Debug complete!');
    }
}