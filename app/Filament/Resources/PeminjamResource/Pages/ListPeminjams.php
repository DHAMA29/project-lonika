<?php

namespace App\Filament\Resources\PeminjamResource\Pages;

use App\Filament\Resources\PeminjamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjams extends ListRecords
{
    protected static string $resource = PeminjamResource::class;
    
    protected static ?string $title = 'Daftar Pelanggan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Daftarkan Pelanggan Baru'),
        ];
    }
}
