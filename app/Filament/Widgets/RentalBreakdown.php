<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RentalBreakdown extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        
        // Peminjaman yang dimulai hari ini
        $rentalHariIni = Peminjaman::where('status', 'belum dikembalikan')
            ->whereDate('tanggal_pinjam', $today)
            ->count();
            
        $itemsHariIni = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($today) {
            $query->where('status', 'belum dikembalikan')
                  ->whereDate('tanggal_pinjam', $today);
        })->sum('jumlah');
        
        // Booking untuk hari mendatang  
        $bookingMendatang = Peminjaman::where('status', 'belum dikembalikan')
            ->whereDate('tanggal_pinjam', '>', $today)
            ->count();
            
        $itemsBookingMendatang = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($today) {
            $query->where('status', 'belum dikembalikan')
                  ->whereDate('tanggal_pinjam', '>', $today);
        })->sum('jumlah');
        
        // Booking untuk masa lalu yang belum dimulai (edge case)
        $bookingTerlambat = Peminjaman::where('status', 'belum dikembalikan')
            ->whereDate('tanggal_pinjam', '<', $today)
            ->count();
            
        $itemsTerlambat = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($today) {
            $query->where('status', 'belum dikembalikan')
                  ->whereDate('tanggal_pinjam', '<', $today);
        })->sum('jumlah');
        
        // Yang harus dikembalikan hari ini
        $pengembalianHariIni = Peminjaman::where('status', 'belum dikembalikan')
            ->whereDate('tanggal_kembali', $today)
            ->count();

        $stats = [
            Stat::make('Rental Aktif Hari Ini', $rentalHariIni)
                ->description("{$itemsHariIni} barang sedang dipinjam")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($rentalHariIni > 0 ? 'success' : 'gray'),
                
            Stat::make('Booking Mendatang', $bookingMendatang)
                ->description("{$itemsBookingMendatang} barang sudah dibooking")
                ->descriptionIcon('heroicon-m-clock')
                ->color($bookingMendatang > 0 ? 'info' : 'gray'),
                
            Stat::make('Harus Kembali Hari Ini', $pengembalianHariIni)
                ->description("Rental yang berakhir hari ini")
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($pengembalianHariIni > 0 ? 'warning' : 'success'),
        ];
        
        // Tambahkan stat untuk booking terlambat jika ada
        if ($bookingTerlambat > 0) {
            $stats[] = Stat::make('Booking Terlambat', $bookingTerlambat)
                ->description("{$itemsTerlambat} barang perlu tindak lanjut")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
        
        return $stats;
    }
}