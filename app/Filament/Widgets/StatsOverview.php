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
    
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $now = Carbon::now();
        
        // Data Stock dengan pemisahan hari ini vs booking hari lain
        $totalStok = Barang::sum('stok');
        
        // Barang yang dipinjam hari ini (tanggal pinjam = hari ini)
        $stokDipinjamHariIni = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($now) {
            $query->where('status', 'belum dikembalikan')
                  ->whereDate('tanggal_pinjam', $now->toDateString());
        })->sum('jumlah');
        
        // Barang yang dibooking untuk hari lain (tanggal pinjam != hari ini tapi masih aktif)
        $stokBookingHariLain = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($now) {
            $query->where('status', 'belum dikembalikan')
                  ->whereDate('tanggal_pinjam', '!=', $now->toDateString());
        })->sum('jumlah');
        
        $totalStokTerpakai = $stokDipinjamHariIni + $stokBookingHariLain;
        $stokTersedia = $totalStok - $totalStokTerpakai;
        $persentaseStokTersedia = $totalStok > 0 ? round(($stokTersedia / $totalStok) * 100, 1) : 0;
        
        // Data Transaksi
        $transaksiAktif = Peminjaman::where('status', 'belum dikembalikan')->count();
        $transaksiSelesai = Peminjaman::where('status', 'selesai')->count();
        $totalPeminjam = Peminjam::count();
        
        // Pendapatan Analysis
        $now = Carbon::now();
        $bulanLalu = Carbon::now()->subMonth();
        
        $pendapatanHariIni = Peminjaman::where('status', 'selesai')
            ->whereDate('updated_at', $now->toDateString())
            ->sum('total_harga');
            
        $pendapatanMingguIni = Peminjaman::where('status', 'selesai')
            ->whereBetween('updated_at', [
                $now->copy()->startOfWeek()->toDateString(),
                $now->copy()->endOfWeek()->toDateString()
            ])
            ->sum('total_harga');
            
        $pendapatanBulanIni = Peminjaman::where('status', 'selesai')
            ->whereYear('updated_at', $now->year)
            ->whereMonth('updated_at', $now->month)
            ->sum('total_harga');
            
        $pendapatanBulanLalu = Peminjaman::where('status', 'selesai')
            ->whereYear('updated_at', $bulanLalu->year)
            ->whereMonth('updated_at', $bulanLalu->month)
            ->sum('total_harga');
            
        // Growth calculation
        $pertumbuhanBulanan = $pendapatanBulanLalu > 0 
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
            : ($pendapatanBulanIni > 0 ? 100 : 0);
            
        // Transaksi bulan ini
        $transaksiBulanIni = Peminjaman::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        return [
            Stat::make('Total Stok', number_format($totalStok))
                ->description("{$stokDipinjamHariIni} dipinjam hari ini • {$stokBookingHariLain} dibooking hari lain")
                ->descriptionIcon('heroicon-m-cube')
                ->color($persentaseStokTersedia < 30 ? 'danger' : ($persentaseStokTersedia < 60 ? 'warning' : 'success'))
                ->chart($this->getStockChart()),
                
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description($pertumbuhanBulanan > 0 
                    ? "+{$pertumbuhanBulanan}% dari bulan lalu" 
                    : ($pertumbuhanBulanan < 0 ? "{$pertumbuhanBulanan}% dari bulan lalu" : "Sama dengan bulan lalu"))
                ->descriptionIcon($pertumbuhanBulanan > 0 ? 'heroicon-m-arrow-trending-up' : ($pertumbuhanBulanan < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->color($pertumbuhanBulanan > 0 ? 'success' : ($pertumbuhanBulanan < 0 ? 'danger' : 'warning'))
                ->chart($this->getRevenueChart()),
                
            Stat::make('Pendapatan Minggu Ini', 'Rp ' . number_format($pendapatanMingguIni, 0, ',', '.'))
                ->description("Hari ini: Rp " . number_format($pendapatanHariIni, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
                
            Stat::make('Transaksi Selesai', number_format($transaksiSelesai))
                ->description("{$transaksiBulanIni} total transaksi bulan ini")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Transaksi Belum Selesai', number_format($transaksiAktif))
                ->description($transaksiAktif > 0 ? "Perlu follow-up segera" : "Semua transaksi selesai")
                ->descriptionIcon($transaksiAktif > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-badge')
                ->color($transaksiAktif > 10 ? 'danger' : ($transaksiAktif > 5 ? 'warning' : ($transaksiAktif > 0 ? 'info' : 'success'))),
                
            Stat::make('Total Peminjam', number_format($totalPeminjam))
                ->description("Member terdaftar")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
    
    private function getStockChart(): array
    {
        // Stock utilization dalam 7 hari terakhir (total yang terpakai)
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $totalBorrowed = DetailPeminjaman::whereHas('peminjaman', function ($query) use ($date) {
                $query->where('status', 'belum dikembalikan')
                      ->whereDate('created_at', '<=', $date);
            })->sum('jumlah');
            
            $data[] = round(($totalBorrowed / max($this->getTotalStock(), 1)) * 100, 1);
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
