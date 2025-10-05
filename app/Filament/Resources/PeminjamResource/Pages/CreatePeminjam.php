<?php

namespace App\Filament\Resources\PeminjamResource\Pages;

use App\Filament\Resources\PeminjamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjam extends CreateRecord
{
    protected static string $resource = PeminjamResource::class;
    
    protected static ?string $title = 'Daftarkan Pelanggan Baru';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
