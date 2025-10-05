<?php

namespace App\Filament\Resources\DiskonResource\Pages;

use App\Filament\Resources\DiskonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiskon extends EditRecord
{
    protected static string $resource = DiskonResource::class;
    
    protected static ?string $title = 'Edit Kupon Diskon';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus Kupon Diskon')
                ->modalDescription('Apakah Anda yakin ingin menghapus kupon diskon ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
