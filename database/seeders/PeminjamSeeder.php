<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use Illuminate\Database\Seeder;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        Peminjam::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
        ]);

        Peminjam::create([
            'nama' => 'Siti Aisyah',
            'email' => 'siti@example.com',
            'telepon' => '081298765432',
            'alamat' => 'Jl. Sudirman No. 45, Bandung',
        ]);
    }
}
