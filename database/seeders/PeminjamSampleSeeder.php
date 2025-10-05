<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjam;

class PeminjamSampleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $sampleData = [
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat'
            ],
            [
                'nama' => 'Siti Rahayu',
                'email' => 'siti.rahayu@gmail.com',
                'telepon' => '081987654321',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta Selatan'
            ],
            [
                'nama' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@yahoo.com',
                'telepon' => '081122334455',
                'alamat' => 'Jl. Gatot Subroto No. 789, Jakarta Barat'
            ],
            [
                'nama' => 'Maria Kristina',
                'email' => 'maria.kristina@hotmail.com',
                'telepon' => '081555666777',
                'alamat' => 'Jl. HR Rasuna Said No. 321, Jakarta Timur'
            ],
            [
                'nama' => 'Budi Hermawan',
                'email' => 'budi.hermawan@email.com',
                'telepon' => '081999888777',
                'alamat' => 'Jl. Thamrin No. 654, Jakarta Pusat'
            ]
        ];

        foreach ($sampleData as $data) {
            Peminjam::create($data);
        }
    }
}
