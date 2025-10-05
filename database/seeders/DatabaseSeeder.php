<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JenisBarangSeeder::class,
            BarangSeeder::class,
            PeminjamSeeder::class,
            PeminjamanSeeder::class,
            DetailPeminjamanSeeder::class,
        ]);
    }
}
