<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    
    protected $fillable = [
        'peminjam_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'lama_hari',
        'pembayaran',
        'status',
        'total_harga',
    ];
    
    protected $casts = [
        'total_harga' => 'decimal:2',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'lama_hari' => 'integer',
    ];

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    protected static function booted()
    {
        // Saat membuat detail peminjaman, kurangi stok
        static::created(function ($peminjaman) {
            foreach ($peminjaman->detail as $detail) {
                $barang = $detail->barang;
                $barang->decrement('stok', $detail->jumlah);
            }
        });

        // Saat status diubah jadi selesai → kembalikan stok
        static::updated(function ($peminjaman) {
            if ($peminjaman->isDirty('status') && $peminjaman->status === 'selesai') {
                foreach ($peminjaman->detail as $detail) {
                    $barang = $detail->barang;
                    $barang->increment('stok', $detail->jumlah);
                }
            }
        });
    }
}
