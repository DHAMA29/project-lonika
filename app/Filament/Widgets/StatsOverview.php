<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Peminjam;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {
        // Data Stock
        $totalStok = Barang::sum('stok');
        $stokDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($query) {
            $query->where('status', 'belum dikembalikan');
        })->sum('jumlah');
        $stokTersedia = $totalStok - $stokDipinjam;
        $persentaseStokTersedia = $totalStok > 0 ? round(($stokTersedia / $totalStok) * 100, 1) : 0;
        
        // Data Transaksi
        $transaksiAktif = Peminjaman::where('status', 'belum dikembalikan')->count();
        $transaksiSelesai = Peminjaman::where('status', 'selesai')->count();
        $totalPeminjam = Peminjam::count();
        
        // Pendapatan Analysis
        $now = Carbon::now();
        $pendapatanHariIni = Peminjaman::where('status', 'selesai')
            ->whereDate('updated_at', $now->toDateString())
            ->sum('total_harga');
            
        $pendapatanMingguIni = Peminjaman::where('status', 'selesai')
            ->whereBetween('updated_at', [
                $now->startOfWeek()->toDateString(),
                $now->endOfWeek()->toDateString()
            ])
            ->sum('total_harga');
            
        $pendapatanBulanIni = Peminjaman::where('status', 'selesai')
            ->whereYear('updated_at', $now->year)
            ->whereMonth('updated_at', $now->month)
            ->sum('total_harga');
            
        $pendapatanBulanLalu = Peminjaman::where('status', 'selesai')
            ->whereYear('updated_at', $now->subMonth()->year)
            ->whereMonth('updated_at', $now->subMonth()->month)
            ->sum('total_harga');
            
        // Growth calculation
        $pertumbuhanBulanan = $pendapatanBulanLalu > 0 
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
            : 0;
            
        // Transaksi bulan ini
        $transaksiBulanIni = Peminjaman::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        return [
            Stat::make('Total Stock', number_format($totalStok))
                ->description("{$stokDipinjam} dipinjam • {$persentaseStokTersedia}% tersedia")
                ->descriptionIcon('heroicon-m-cube')
                ->color($persentaseStokTersedia < 30 ? 'danger' : ($persentaseStokTersedia < 60 ? 'warning' : 'success'))
                ->chart($this->getStockChart()),
                
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description($pertumbuhanBulanan >= 0 
                    ? "+{$pertumbuhanBulanan}% dari bulan lalu" 
                    : "{$pertumbuhanBulanan}% dari bulan lalu")
                ->descriptionIcon($pertumbuhanBulanan >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($pertumbuhanBulanan >= 0 ? 'success' : 'danger')
                ->chart($this->getRevenueChart()),
                
            Stat::make('Pendapatan Minggu Ini', 'Rp ' . number_format($pendapatanMingguIni, 0, ',', '.'))
                ->description("Hari ini: Rp " . number_format($pendapatanHariIni, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
                
            Stat::make('Transaksi Selesai', $transaksiSelesai)
                ->description("{$transaksiBulanIni} total transaksi bulan ini")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Transaksi Belum Selesai', $transaksiAktif)
                ->description("Perlu follow-up segera")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($transaksiAktif > 10 ? 'danger' : ($transaksiAktif > 5 ? 'warning' : ($transaksiAktif > 0 ? 'info' : 'success'))),
                
            Stat::make('Total Peminjam', $totalPeminjam)
                ->description("Member terdaftar")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
    
    private function getStockChart(): array
    {
        // Stock utilization dalam 7 hari terakhir
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $borrowed = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($date) {
                $query->where('status', 'belum dikembalikan')
                      ->whereDate('created_at', '<=', $date);
            })->sum('jumlah');
            
            $data[] = round(($borrowed / max($this->getTotalStock(), 1)) * 100, 1);
        }
        return $data;
    }
    
    private function getRevenueChart(): array
    {
        // Revenue dalam 7 hari terakhir
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Peminjaman::where('status', 'selesai')
                ->whereDate('updated_at', $date)
                ->sum('total_harga');
            $data[] = $revenue / 1000; // Dalam ribuan untuk chart
        }
        return $data;
    }
    
    private function getTotalStock(): int
    {
        return Barang::sum('stok');
    }
}
