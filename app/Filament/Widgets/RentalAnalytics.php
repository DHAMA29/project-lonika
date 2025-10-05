<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RentalAnalytics extends ChartWidget
{
    protected static ?string $heading = 'Analisis Penyewaan (7 Hari Terakhir)';
    
    protected static ?string $maxHeight = '300px';
    
    protected static ?int $sort = 2;
    
    protected static ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $data = $this->getRentalData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Transaksi Baru',
                    'data' => $data['newRentals'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Transaksi Selesai',
                    'data' => $data['completedRentals'],
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    private function getRentalData(): array
    {
        $labels = [];
        $newRentals = [];
        $completedRentals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            // Transaksi baru per hari
            $newCount = Peminjaman::whereDate('created_at', $date->toDateString())->count();
            $newRentals[] = $newCount;

            // Transaksi selesai per hari
            $completedCount = Peminjaman::where('status', 'selesai')
                ->whereDate('updated_at', $date->toDateString())
                ->count();
            $completedRentals[] = $completedCount;
        }

        return [
            'labels' => $labels,
            'newRentals' => $newRentals,
            'completedRentals' => $completedRentals,
        ];
    }
}