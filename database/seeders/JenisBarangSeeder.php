<?php

namespace Database\Seeders;

use App\Models\JenisBarang;
use Illuminate\Database\Seeder;

class JenisBarangSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = ['Kamera', 'Audio', 'Lighting', 'Aksesoris'];

        foreach ($jenis as $j) {
            JenisBarang::create(['nama' => $j]);
        }
    }
}
