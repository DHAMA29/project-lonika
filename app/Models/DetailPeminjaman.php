<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    protected $fillable = ['peminjaman_id','barang_id','jumlah','harga','subtotal'];
    
    protected $casts = [
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'jumlah' => 'integer',
    ];
    
    protected $attributes = [
        'harga' => 0,
        'subtotal' => 0,
        'jumlah' => 1,
    ];

    public function peminjaman() {
        return $this->belongsTo(Peminjaman::class);
    }

    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}
