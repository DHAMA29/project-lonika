<?php

namespace App\Filament\Resources\PeminjamanResource\Pages;

use App\Filament\Resources\PeminjamanResource;
use App\Models\Barang;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure detail_peminjaman has proper values
        if (isset($data['detail_peminjaman'])) {
            $totalHarga = 0;
            foreach ($data['detail_peminjaman'] as $index => $detail) {
                // Ensure harga is set
                if (empty($detail['harga']) && !empty($detail['barang_id'])) {
                    $barang = Barang::find($detail['barang_id']);
                    if ($barang) {
                        $data['detail_peminjaman'][$index]['harga'] = $barang->harga_hari;
                    }
                }
                
                // Clean up formatted numbers if they exist
                if (isset($detail['harga']) && is_string($detail['harga'])) {
                    $data['detail_peminjaman'][$index]['harga'] = floatval(str_replace(['.', ','], ['', '.'], $detail['harga']));
                }
                if (isset($detail['subtotal']) && is_string($detail['subtotal'])) {
                    $data['detail_peminjaman'][$index]['subtotal'] = floatval(str_replace(['.', ','], ['', '.'], $detail['subtotal']));
                }
                
                // Ensure subtotal is calculated
                if (isset($detail['harga']) && isset($detail['jumlah'])) {
                    $lamaDays = intval($data['lama_hari'] ?? 1);
                    $harga = floatval($data['detail_peminjaman'][$index]['harga'] ?? 0);
                    $jumlah = intval($detail['jumlah'] ?? 1);
                    $subtotal = $harga * $jumlah * $lamaDays;
                    $data['detail_peminjaman'][$index]['subtotal'] = $subtotal;
                    $totalHarga += $subtotal;
                }
                
                // Convert to proper numeric types
                $data['detail_peminjaman'][$index]['harga'] = floatval($data['detail_peminjaman'][$index]['harga'] ?? 0);
                $data['detail_peminjaman'][$index]['subtotal'] = floatval($data['detail_peminjaman'][$index]['subtotal'] ?? 0);
                $data['detail_peminjaman'][$index]['jumlah'] = intval($data['detail_peminjaman'][$index]['jumlah'] ?? 1);
            }
            
            // Set calculated total_harga
            $data['total_harga'] = $totalHarga;
        }
        
        // Clean up total_harga if it's formatted
        if (isset($data['total_harga']) && is_string($data['total_harga'])) {
            $data['total_harga'] = floatval(str_replace(['.', ','], ['', '.'], $data['total_harga']));
        }
        
        return $data;
    }
}
