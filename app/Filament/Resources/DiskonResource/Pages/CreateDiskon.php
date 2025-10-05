<?php

namespace App\Filament\Resources\DiskonResource\Pages;

use App\Filament\Resources\DiskonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDiskon extends CreateRecord
{
    protected static string $resource = DiskonResource::class;
    
    protected static ?string $title = 'Tambah Kupon Diskon';
    
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Use the correct model and allow mass assignment
        return \App\Models\DiskonModel::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
