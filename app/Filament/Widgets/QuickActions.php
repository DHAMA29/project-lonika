<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Tambah Barang',
                    'url' => route('filament.admin.resources.barangs.create'),
                    'icon' => 'heroicon-o-camera',
                    'color' => 'success', 
                    'description' => 'Tambah barang ke inventory'
                ],
                [
                    'label' => 'Tambah Pelanggan',
                    'url' => route('filament.admin.resources.peminjams.create'),
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'info',
                    'description' => 'Daftarkan pelanggan baru'
                ],
                [
                    'label' => 'Tambah Kupon Diskon',
                    'url' => route('filament.admin.resources.diskons.create'),
                    'icon' => 'heroicon-o-ticket',
                    'color' => 'warning',
                    'description' => 'Tambah kupon diskon promotional'
                ],
            ]
        ];
    }
}
