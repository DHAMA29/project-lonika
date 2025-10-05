<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Barang::truncate();
        
        $barangData = [
            // Kamera Category (jenis_barang_id: 1)
            [
                'jenis_barang_id' => 1,
                'nama' => 'Camera Sony NX 100',
                'deskripsi' => 'Kamera profesional Sony NX 100 dengan kualitas gambar tinggi dan fitur recording 4K untuk produksi video berkualitas.',
                'stok' => 5,
                'harga_hari' => 200000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 1,
                'nama' => 'Camera Sony PX 100',
                'deskripsi' => 'Kamera Sony PX 100 dengan sensor full frame dan stabilisasi gambar untuk fotografi dan videografi profesional.',
                'stok' => 1,
                'harga_hari' => 200000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 1,
                'nama' => 'Camera Miroles Sony A 6000',
                'deskripsi' => 'Kamera mirrorless Sony A6000 dengan autofocus cepat dan kualitas gambar excellent untuk content creator.',
                'stok' => 1,
                'harga_hari' => 100000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 1,
                'nama' => 'Camera Miroles Sony A mark II',
                'deskripsi' => 'Kamera mirrorless Sony A Mark II dengan teknologi terdepan dan performa tinggi untuk fotografer profesional.',
                'stok' => 1,
                'harga_hari' => 150000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 1,
                'nama' => 'Camera Miroles Sony Mark III',
                'deskripsi' => 'Kamera mirrorless Sony Mark III dengan fitur canggih dan kualitas gambar superior untuk kebutuhan profesional.',
                'stok' => 1,
                'harga_hari' => 250000,
                'gambar' => null,
            ],

            // Audio Category (jenis_barang_id: 2)
            [
                'jenis_barang_id' => 2,
                'nama' => 'Wireles Video Magnus',
                'deskripsi' => 'Wireless video transmitter Magnus untuk transmisi video tanpa kabel dengan kualitas HD.',
                'stok' => 1,
                'harga_hari' => 100000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 2,
                'nama' => 'Wireles Video Accoon',
                'deskripsi' => 'Wireless video system Accoon dengan teknologi transmission yang stabil dan low latency.',
                'stok' => 1,
                'harga_hari' => 25000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 2,
                'nama' => 'Layar Lebar 3 m',
                'deskripsi' => 'Layar lebar 3 meter untuk proyeksi besar dengan kualitas visual yang excellent.',
                'stok' => 6,
                'harga_hari' => 100000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 2,
                'nama' => 'Layar Lebar 6 m',
                'deskripsi' => 'Layar lebar 6 meter untuk event besar dan presentasi skala luas dengan kualitas premium.',
                'stok' => 1,
                'harga_hari' => 300000,
                'gambar' => null,
            ],

            // Lighting Category (jenis_barang_id: 3)
            [
                'jenis_barang_id' => 3,
                'nama' => 'Lighting Hinomaru 150 w',
                'deskripsi' => 'Lighting Hinomaru 150W dengan output cahaya tinggi untuk penerangan studio dan photography.',
                'stok' => 2,
                'harga_hari' => 15000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 3,
                'nama' => 'Lighting Godox 60',
                'deskripsi' => 'Lighting Godox 60W dengan kontrol temperature dan dimmer untuk studio lighting profesional.',
                'stok' => 1,
                'harga_hari' => 15000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 3,
                'nama' => 'Lighting Inbac 900v',
                'deskripsi' => 'Lighting Inbac 900V dengan power tinggi dan stabilitas untuk kebutuhan lighting heavy duty.',
                'stok' => 4,
                'harga_hari' => 15000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 3,
                'nama' => 'Extender Bafo 200m',
                'deskripsi' => 'Signal extender Bafo 200m untuk memperpanjang jangkauan signal dengan kualitas terjaga.',
                'stok' => 6,
                'harga_hari' => 50000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 3,
                'nama' => 'Extender Bafo 60 m',
                'deskripsi' => 'Signal extender Bafo 60m compact untuk kebutuhan extension signal jarak menengah.',
                'stok' => 2,
                'harga_hari' => 25000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 3,
                'nama' => 'Screen Projector 70 Inch',
                'deskripsi' => 'Screen projector 70 inch dengan material berkualitas tinggi untuk hasil proyeksi yang optimal.',
                'stok' => 5,
                'harga_hari' => 50000,
                'gambar' => null,
            ],

            // Drone Category (jenis_barang_id: 4) - No items in current data

            // Komputer Category (jenis_barang_id: 5) - No items in current data

            // Projektor Category (jenis_barang_id: 6)
            [
                'jenis_barang_id' => 6,
                'nama' => 'lcd projector x 51',
                'deskripsi' => 'LCD Projector X51 dengan resolusi tinggi untuk presentasi dan proyeksi visual berkualitas.',
                'stok' => 2,
                'harga_hari' => 175000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 6,
                'nama' => 'Lcd projector x 400',
                'deskripsi' => 'LCD Projector X400 dengan brightness tinggi dan kualitas gambar excellent untuk event dan presentasi besar.',
                'stok' => 2,
                'harga_hari' => 150000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 6,
                'nama' => 'Lcd Projector X10',
                'deskripsi' => 'LCD Projector X10 portable dengan kemudahan penggunaan untuk presentasi mobile dan event kecil.',
                'stok' => 1,
                'harga_hari' => 150000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 6,
                'nama' => 'Lcd Projector X18',
                'deskripsi' => 'LCD Projector X18 dengan fitur canggih dan kualitas proyeksi superior untuk kebutuhan profesional.',
                'stok' => 1,
                'harga_hari' => 150000,
                'gambar' => null,
            ],

            // Kabel Category (jenis_barang_id: 7)
            [
                'jenis_barang_id' => 7,
                'nama' => 'Kabel Line AC 20m',
                'deskripsi' => 'Kabel audio line AC 20 meter dengan kualitas signal transmission yang excellent untuk setup audio profesional.',
                'stok' => 6,
                'harga_hari' => 5000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'Kabel Line AC 10m',
                'deskripsi' => 'Kabel audio line AC 10 meter untuk koneksi audio dengan kualitas suara yang jernih dan stabil.',
                'stok' => 6,
                'harga_hari' => 5000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'kabel HDMI L5m',
                'deskripsi' => 'Kabel HDMI 5 meter dengan support 4K resolution untuk koneksi video berkualitas tinggi.',
                'stok' => 15,
                'harga_hari' => 2000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'kabel HDMI F0 20 m',
                'deskripsi' => 'Kabel HDMI F0 20 meter untuk jarak jauh dengan maintain kualitas signal yang optimal.',
                'stok' => 3,
                'harga_hari' => 10000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'kabel HDMI f0 10 m',
                'deskripsi' => 'Kabel HDMI F0 10 meter dengan performa reliability tinggi untuk koneksi audio video.',
                'stok' => 3,
                'harga_hari' => 15000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'kabel HDMI F0 50m',
                'deskripsi' => 'Kabel HDMI F0 50 meter untuk instalasi jarak sangat jauh dengan teknologi signal booster.',
                'stok' => 2,
                'harga_hari' => 25000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'Kabel UTP 150 m',
                'deskripsi' => 'Kabel UTP 150 meter untuk networking dan data transmission dengan kecepatan tinggi.',
                'stok' => 4,
                'harga_hari' => 25000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'kabel UTP 100 m',
                'deskripsi' => 'Kabel UTP 100 meter Cat6 untuk network installation dengan performa optimal.',
                'stok' => 3,
                'harga_hari' => 10000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 7,
                'nama' => 'Kabel HDMI Lindi',
                'deskripsi' => 'Kabel HDMI Lindi dengan teknologi khusus untuk transmisi signal yang superior.',
                'stok' => 3,
                'harga_hari' => 5000,
                'gambar' => null,
            ],

            // Modem Category (jenis_barang_id: 8)
            [
                'jenis_barang_id' => 8,
                'nama' => 'modem Mikrotic LHG Lite',
                'deskripsi' => 'Modem Mikrotik LHG Lite untuk koneksi wireless jarak jauh dengan performa tinggi.',
                'stok' => 2,
                'harga_hari' => 50000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 8,
                'nama' => 'Modem Orbit',
                'deskripsi' => 'Modem Orbit portable untuk koneksi internet mobile dengan kecepatan tinggi.',
                'stok' => 1,
                'harga_hari' => 50000,
                'gambar' => null,
            ],

            // Lensa Category (jenis_barang_id: 9)
            [
                'jenis_barang_id' => 9,
                'nama' => 'Lensa Kit 35mm',
                'deskripsi' => 'Lensa kit 35mm dengan kualitas optik tinggi, cocok untuk fotografi portrait dan landscape.',
                'stok' => 1,
                'harga_hari' => 50000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 9,
                'nama' => 'Lensa kit 85',
                'deskripsi' => 'Lensa 85mm dengan aperture lebar untuk fotografi portrait dengan bokeh yang indah.',
                'stok' => 1,
                'harga_hari' => 50000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 9,
                'nama' => 'Lensa zoom 200',
                'deskripsi' => 'Lensa zoom 200mm untuk fotografi jarak jauh dengan kualitas gambar yang tajam dan detail.',
                'stok' => 1,
                'harga_hari' => 150000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 9,
                'nama' => 'lensa fix manual 35',
                'deskripsi' => 'Lensa fix manual 35mm dengan kontrol manual penuh untuk fotografi artistic dan creative.',
                'stok' => 1,
                'harga_hari' => 50000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 9,
                'nama' => 'Lensa Fix 50/1.2',
                'deskripsi' => 'Lensa fix 50mm f/1.2 dengan aperture super lebar untuk low light photography dan bokeh extreme.',
                'stok' => 1,
                'harga_hari' => 75000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 9,
                'nama' => 'Gimbal Webil',
                'deskripsi' => 'Gimbal stabilizer profesional untuk smartphone dan kamera kecil, menghasilkan video yang stabil dan smooth.',
                'stok' => 2,
                'harga_hari' => 50000,
                'gambar' => null,
            ],

            // Baterai Category (jenis_barang_id: 10)
            [
                'jenis_barang_id' => 10,
                'nama' => 'Batrai NPF 970',
                'deskripsi' => 'Battery NPF 970 dengan kapasitas tinggi untuk kamera dan peralatan video profesional, tahan lama dan reliable.',
                'stok' => 12,
                'harga_hari' => 5000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 10,
                'nama' => 'Batrai NPF 770',
                'deskripsi' => 'Battery NPF 770 dengan performa optimal untuk berbagai peralatan elektronik dan kamera digital.',
                'stok' => 1,
                'harga_hari' => 3000,
                'gambar' => null,
            ],
            [
                'jenis_barang_id' => 10,
                'nama' => 'Batrai NPF570',
                'deskripsi' => 'Battery NPF 570 compact dengan daya yang cukup untuk penggunaan sehari-hari peralatan elektronik.',
                'stok' => 4,
                'harga_hari' => 2000,
                'gambar' => null,
            ],
        ];

        foreach ($barangData as $data) {
            Barang::create($data);
        }
    }
}
