<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use Illuminate\Database\Seeder;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        // Data pelanggan yang menyewa peralatan
        Peminjam::create([
            'nama' => 'PT. Kreatif Media Indonesia',
            'email' => 'info@kreatifmedia.com',
            'telepon' => '021-55567890',
            'alamat' => 'Jl. Gatot Subroto No. 123, Jakarta Selatan',
        ]);

        Peminjam::create([
            'nama' => 'CV. Dokumentasi Prima',
            'email' => 'booking@dokprima.com',
            'telepon' => '022-87654321',
            'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
        ]);

        Peminjam::create([
            'nama' => 'Studio Foto Cahaya',
            'email' => 'rental@studiocahaya.com',
            'telepon' => '031-11223344',
            'alamat' => 'Jl. Pemuda No. 78, Surabaya',
        ]);

        Peminjam::create([
            'nama' => 'Event Organizer Sukses',
            'email' => 'eo@eventosukses.com',
            'telepon' => '0274-998877',
            'alamat' => 'Jl. Malioboro No. 156, Yogyakarta',
        ]);

        Peminjam::create([
            'nama' => 'Produksi Film Nusantara',
            'email' => 'produksi@filmnusantara.com',
            'telepon' => '0361-556677',
            'alamat' => 'Jl. Sunset Road No. 99, Denpasar, Bali',
        ]);

        Peminjam::create([
            'nama' => 'Wedding Organizer Bahagia',
            'email' => 'wo@weddingbahagia.com',
            'telepon' => '0251-334455',
            'alamat' => 'Jl. Pajajaran No. 234, Bogor',
        ]);

        Peminjam::create([
            'nama' => 'Komunitas Fotografi Jakarta',
            'email' => 'admin@kfjakarta.org',
            'telepon' => '021-77889900',
            'alamat' => 'Jl. Kemang Raya No. 67, Jakarta Selatan',
        ]);

        Peminjam::create([
            'nama' => 'Videografer Freelance',
            'email' => 'contact@videofreelance.com',
            'telepon' => '0812-3456-7890',
            'alamat' => 'Jl. Cihampelas No. 112, Bandung',
        ]);
    }
}
