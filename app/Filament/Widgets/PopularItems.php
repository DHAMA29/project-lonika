<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PopularItems extends BaseWidget
{
    // Widget disabled - akan dihapus dari dashboard
    protected static bool $isLazy = false;
    
    protected static ?string $heading = 'Widget Tidak Aktif';
    
    protected static ?int $sort = 999; // Pindah ke urutan terakhir
    
    protected int | string | array $columnSpan = 'full';
    
    // Nonaktifkan widget dengan return null
    public static function canView(): bool
    {
        return false; // Widget tidak akan ditampilkan
    }

    protected function getTableQuery(): Builder
    {
        return Barang::query()->limit(0); // Return empty query
    }

    protected function getTableColumns(): array
    {
        return []; // Return empty columns
    }
}