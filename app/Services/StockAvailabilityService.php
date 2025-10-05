<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockAvailabilityService
{
    /**
     * Check if a product is available for a specific date range
     * 
     * @param int $barangId
     * @param string $startDate
     * @param string $endDate
     * @param int $quantity
     * @param int|null $excludePeminjamanId
     * @return array
     */
    public function checkAvailability($barangId, $startDate, $endDate, $quantity = 1, $excludePeminjamanId = null)
    {
        $barang = Barang::find($barangId);
        
        if (!$barang) {
            return [
                'available' => false,
                'message' => 'Barang tidak ditemukan',
                'available_quantity' => 0,
                'total_stock' => 0
            ];
        }

        // Parse dates
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Get all active rentals that overlap with requested period
        $overlappingRentals = $this->getOverlappingRentals($barangId, $start, $end, $excludePeminjamanId);

        // Calculate how much stock is already booked during this period
        $bookedQuantity = $overlappingRentals->sum(function ($rental) use ($barangId) {
            return $rental->detail->where('barang_id', $barangId)->sum('jumlah');
        });

        $availableQuantity = $barang->stok - $bookedQuantity;

        return [
            'available' => $availableQuantity >= $quantity,
            'message' => $availableQuantity >= $quantity 
                ? 'Barang tersedia untuk periode ini'
                : "Hanya tersedia {$availableQuantity} unit untuk periode ini",
            'available_quantity' => max(0, $availableQuantity),
            'total_stock' => $barang->stok,
            'booked_quantity' => $bookedQuantity,
            'requested_quantity' => $quantity
        ];
    }

    /**
     * Get rentals that overlap with the specified date range
     */
    private function getOverlappingRentals($barangId, Carbon $start, Carbon $end, $excludePeminjamanId = null)
    {
        $query = Peminjaman::whereHas('detail', function ($query) use ($barangId) {
            $query->where('barang_id', $barangId);
        })
        ->with(['detail' => function ($query) use ($barangId) {
            $query->where('barang_id', $barangId);
        }])
        ->where(function ($query) use ($start, $end) {
            // Rental periods that overlap with requested period
            $query->where(function ($q) use ($start, $end) {
                // Case 1: Rental starts before or during requested period and ends during or after
                $q->where('tanggal_pinjam', '<=', $end)
                  ->where('tanggal_kembali', '>=', $start);
            });
        })
        ->whereIn('status', ['belum dikembalikan']); // Use valid status values

        if ($excludePeminjamanId) {
            $query->where('id', '!=', $excludePeminjamanId);
        }

        return $query->get();
    }

    /**
     * Reserve stock for a rental (when booking is created)
     */
    public function reserveStock($peminjamanId)
    {
        $peminjaman = Peminjaman::with('detail.barang')->find($peminjamanId);
        
        if (!$peminjaman || $peminjaman->stock_reserved) {
            return false;
        }

        DB::beginTransaction();
        try {
            // Mark as reserved but don't reduce actual stock yet
            $peminjaman->update([
                'stock_reserved' => true
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to reserve stock: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deduct stock when rental period starts
     */
    public function deductStock($peminjamanId)
    {
        $peminjaman = Peminjaman::with('detail.barang')->find($peminjamanId);
        
        if (!$peminjaman || $peminjaman->stock_deducted) {
            return false;
        }

        DB::beginTransaction();
        try {
            foreach ($peminjaman->detail as $detail) {
                $barang = $detail->barang;
                
                // Check if we still have enough stock
                if ($barang->stok < $detail->jumlah) {
                    throw new \Exception("Insufficient stock for {$barang->nama}");
                }
                
                // Reduce actual stock
                $barang->decrement('stok', $detail->jumlah);
            }

            // Mark stock as deducted
            $peminjaman->update([
                'stock_deducted' => true,
                'stock_deduction_date' => now(),
                'status' => 'belum dikembalikan' // Keep using existing status
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to deduct stock: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Return stock when rental period ends
     */
    public function returnStock($peminjamanId)
    {
        $peminjaman = Peminjaman::with('detail.barang')->find($peminjamanId);
        
        if (!$peminjaman || $peminjaman->stock_returned || !$peminjaman->stock_deducted) {
            return false;
        }

        DB::beginTransaction();
        try {
            foreach ($peminjaman->detail as $detail) {
                $barang = $detail->barang;
                
                // Return stock
                $barang->increment('stok', $detail->jumlah);
            }

            // Mark stock as returned
            $peminjaman->update([
                'stock_returned' => true,
                'stock_return_date' => now(),
                'status' => 'selesai'
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to return stock: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get availability for multiple dates (useful for calendar view)
     */
    public function getAvailabilityCalendar($barangId, $startDate, $endDate)
    {
        $barang = Barang::find($barangId);
        if (!$barang) {
            return [];
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $calendar = [];

        while ($start->lte($end)) {
            $dayStart = $start->copy()->startOfDay();
            $dayEnd = $start->copy()->endOfDay();
            
            $availability = $this->checkAvailability($barangId, $dayStart, $dayEnd, 1);
            
            $calendar[$start->format('Y-m-d')] = [
                'date' => $start->format('Y-m-d'),
                'available_quantity' => $availability['available_quantity'],
                'is_available' => $availability['available_quantity'] > 0
            ];
            
            $start->addDay();
        }

        return $calendar;
    }

    /**
     * Process pending stock operations based on dates
     */
    public function processScheduledStockOperations()
    {
        $now = Carbon::now();
        
        // Process stock deductions ONLY for rentals that should start TODAY (not past dates)
        // Stock should only be deducted on the actual rental start date, not before
        $pendingDeductions = Peminjaman::where('stock_reserved', true)
            ->where('stock_deducted', false)
            ->whereDate('tanggal_pinjam', '=', $now->format('Y-m-d'))  // FIXED: Only TODAY, not <= 
            ->whereTime('tanggal_pinjam', '<=', $now->format('H:i:s'))
            ->get();

        \Log::info('Stock deduction check', [
            'current_date' => $now->format('Y-m-d H:i:s'),
            'pending_deductions_count' => $pendingDeductions->count(),
            'pending_deductions_ids' => $pendingDeductions->pluck('id')->toArray()
        ]);

        foreach ($pendingDeductions as $peminjaman) {
            $this->deductStock($peminjaman->id);
        }

        // Process stock returns for rentals that should end today
        $pendingReturns = Peminjaman::where('stock_deducted', true)
            ->where('stock_returned', false)
            ->where('status', 'belum dikembalikan') // Add status filter
            ->whereDate('tanggal_kembali', '<=', $now->format('Y-m-d'))
            ->whereTime('tanggal_kembali', '<=', $now->format('H:i:s'))
            ->get();

        foreach ($pendingReturns as $peminjaman) {
            $this->returnStock($peminjaman->id);
        }

        return [
            'deductions_processed' => $pendingDeductions->count(),
            'returns_processed' => $pendingReturns->count()
        ];
    }
}