<?php

namespace App\Filament\Resources\JenisBarangResource\Pages;

use App\Filament\Resources\JenisBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisBarang extends EditRecord
{
    protected static string $resource = JenisBarangResource::class;
    
    protected static ?string $title = 'Edit Jenis Barang';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus Jenis Barang')
                ->modalDescription('Apakah Anda yakin ingin menghapus jenis barang ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
