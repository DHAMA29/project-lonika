<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    
    protected $fillable = [
        'peminjam_id',
        'kode_transaksi',
        'kode_diskon',
        'nominal_diskon',
        'tanggal_pinjam',
        'tanggal_kembali',
        'lama_hari',
        'pembayaran',
        'status',
        'total_harga',
        'stock_reserved',
        'stock_deducted',
        'stock_returned',
        'stock_deduction_date',
        'stock_return_date',
    ];
    
    protected $casts = [
        'total_harga' => 'decimal:2',
        'nominal_diskon' => 'decimal:2',
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'lama_hari' => 'integer',
        'stock_reserved' => 'boolean',
        'stock_deducted' => 'boolean',
        'stock_returned' => 'boolean',
        'stock_deduction_date' => 'datetime',
        'stock_return_date' => 'datetime',
    ];

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function diskon()
    {
        return $this->belongsTo(DiskonModel::class, 'kode_diskon', 'kode_diskon');
    }

    public static function generateTransactionCode()
    {
        do {
            // Get current year and month for prefix
            $prefix = date('Ym'); // YYYYMM format
            
            // Get the latest transaction code for current month
            $latestTransaction = self::where('kode_transaksi', 'LIKE', $prefix . '%')
                ->orderBy('kode_transaksi', 'desc')
                ->first();
            
            if ($latestTransaction) {
                // Extract the sequential number from the latest code
                $lastSequence = (int) substr($latestTransaction->kode_transaksi, 6);
                $nextSequence = $lastSequence + 1;
            } else {
                // First transaction of the month
                $nextSequence = 1;
            }
            
            // Generate new code: YYYYMM + 4-digit sequence
            $newCode = $prefix . sprintf('%04d', $nextSequence);
            
        } while (self::where('kode_transaksi', $newCode)->exists());
        
        return $newCode;
    }

    protected static function booted()
    {
        // Generate kode transaksi saat membuat peminjaman baru
        static::creating(function ($peminjaman) {
            if (empty($peminjaman->kode_transaksi)) {
                // Retry mechanism untuk memastikan keunikan
                $maxRetries = 5;
                $retryCount = 0;
                
                do {
                    $peminjaman->kode_transaksi = self::generateTransactionCode();
                    $retryCount++;
                } while (
                    self::where('kode_transaksi', $peminjaman->kode_transaksi)->exists() && 
                    $retryCount < $maxRetries
                );
                
                if ($retryCount >= $maxRetries) {
                    throw new \Exception('Gagal generate kode transaksi unik setelah ' . $maxRetries . ' percobaan');
                }
            }
        });

        // REMOVED: Old logic that immediately reduced stock
        // NEW: Stock will be managed by StockAvailabilityService based on actual rental dates
        
        // Saat peminjaman dibuat, hanya reserve stock (tidak mengurangi stok aktual)
        static::created(function ($peminjaman) {
            $stockService = app(\App\Services\StockAvailabilityService::class);
            $stockService->reserveStock($peminjaman->id);
        });

        // Handle status changes
        static::updated(function ($peminjaman) {
            // If status changed to cancelled, release reserved stock
            if ($peminjaman->isDirty('status') && $peminjaman->status === 'cancelled') {
                // Note: 'cancelled' is not in current enum, so this might not work
                // Consider adding proper cancellation logic
                $peminjaman->update([
                    'stock_reserved' => false,
                    'stock_deducted' => false,
                    'stock_returned' => false
                ]);
            }
            
            // If manually marked as returned, return the stock
            if ($peminjaman->isDirty('status') && in_array($peminjaman->status, ['selesai'])) {
                if ($peminjaman->stock_deducted && !$peminjaman->stock_returned) {
                    $stockService = app(\App\Services\StockAvailabilityService::class);
                    $stockService->returnStock($peminjaman->id);
                }
            }
        });
    }
}
