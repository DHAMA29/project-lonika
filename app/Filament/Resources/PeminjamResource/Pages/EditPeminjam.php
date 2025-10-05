<?php

namespace App\Filament\Resources\PeminjamResource\Pages;

use App\Filament\Resources\PeminjamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeminjam extends EditRecord
{
    protected static string $resource = PeminjamResource::class;
    
    protected static ?string $title = 'Edit Pelanggan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus Pelanggan')
                ->modalDescription('Apakah Anda yakin ingin menghapus pelanggan ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
