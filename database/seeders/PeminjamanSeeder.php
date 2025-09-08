<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $peminjaman = Peminjaman::create([
            'peminjam_id' => 1,
            'tanggal_pinjam' => now()->subDays(2),
            'tanggal_kembali' => now()->addDays(3),
            'lama_hari' => 5,
            'pembayaran' => 'transfer',
            'status' => 'ongoing',
            'total_harga' => 400000,
        ]);

        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => 1,
            'jumlah' => 1,
            'harga' => 150000,
            'subtotal' => 150000,
        ]);

        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => 2,
            'jumlah' => 2,
            'harga' => 50000,
            'subtotal' => 100000,
        ]);

        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => 3,
            'jumlah' => 2,
            'harga' => 75000,
            'subtotal' => 150000,
        ]);
    }
}
