<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Barang::create([
            'jenis_id' => 1,
            'nama' => 'Canon EOS R6',
            'stok' => 5,
            'harga_hari' => 150000,
            'foto' => 'barang/canon-r6.jpg',
        ]);

        Barang::create([
            'jenis_id' => 2,
            'nama' => 'Mic Rode Wireless Go',
            'stok' => 10,
            'harga_hari' => 50000,
            'foto' => 'barang/mic-rode.jpg',
        ]);

        Barang::create([
            'jenis_id' => 3,
            'nama' => 'Godox SL-60W Lighting',
            'stok' => 3,
            'harga_hari' => 75000,
            'foto' => 'barang/godox-sl60.jpg',
        ]);
    }
}
