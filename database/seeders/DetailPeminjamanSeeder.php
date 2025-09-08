<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;

class DetailPeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua peminjaman yang ada
        $peminjamanList = Peminjaman::all();

        foreach ($peminjamanList as $peminjaman) {
            // Ambil 1-3 barang secara random
            $barangs = Barang::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($barangs as $barang) {
                $jumlah = rand(1, 2);
                $harga = $barang->harga_hari;
                $subtotal = $jumlah * $harga * $peminjaman->lama_hari;

                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ]);
            }
        }
    }
}
