<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DiskonModel;

class DiskonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = [
            [
                'kode_diskon' => 'PROMO5',
                'persentase' => 5.00,
                'deskripsi' => 'Diskon 5% untuk pelanggan baru',
                'status' => 'aktif',
                'jenis_kode' => 'manual',
                'batas_penggunaan' => 100,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(30),
            ],
            [
                'kode_diskon' => 'SAVE10',
                'persentase' => 10.00,
                'deskripsi' => 'Diskon 10% untuk peminjaman pertama',
                'status' => 'aktif',
                'jenis_kode' => 'manual',
                'batas_penggunaan' => 50,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(60),
            ],
            [
                'kode_diskon' => 'HEMAT15',
                'persentase' => 15.00,
                'deskripsi' => 'Diskon khusus 15% untuk member',
                'status' => 'aktif',
                'jenis_kode' => 'manual',
                'batas_penggunaan' => 25,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(90),
            ],
            [
                'kode_diskon' => 'BONUS20',
                'persentase' => 20.00,
                'deskripsi' => 'Diskon acak 20% terbatas',
                'status' => 'aktif',
                'jenis_kode' => 'acak',
                'batas_penggunaan' => 10,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(14),
            ],
        ];

        foreach ($discounts as $discount) {
            DiskonModel::create($discount);
        }
    }
}
