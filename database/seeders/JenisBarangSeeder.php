<?php

namespace Database\Seeders;

use App\Models\JenisBarang;
use Illuminate\Database\Seeder;

class JenisBarangSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        JenisBarang::truncate();
        
        $jenisBarang = [
            ['id' => 1, 'nama' => 'Kamera'],
            ['id' => 2, 'nama' => 'Audio'],
            ['id' => 3, 'nama' => 'Lighting'],
            ['id' => 4, 'nama' => 'Drone'],
            ['id' => 5, 'nama' => 'Komputer'],
            ['id' => 6, 'nama' => 'Projektor'],
            ['id' => 7, 'nama' => 'Kabel'],
            ['id' => 8, 'nama' => 'Modem'],
            ['id' => 9, 'nama' => 'Lensa'],
            ['id' => 10, 'nama' => 'Baterai'],
        ];

        foreach ($jenisBarang as $jenis) {
            JenisBarang::create($jenis);
        }
    }
}
