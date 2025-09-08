<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisBarangResource\Pages;
use App\Models\JenisBarang;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;

class JenisBarangResource extends Resource
{
    protected static ?string $model = JenisBarang::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Jenis Barang';
    protected static ?string $pluralModelLabel = 'Jenis Barang';
    protected static ?string $modelLabel = 'Jenis Barang';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jenis Barang')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Jenis Barang')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Kamera, Laptop, Proyektor')
                            ->unique(ignoreRecord: true),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Jenis')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('total_stok')
                    ->label('Total Unit')
                    ->getStateUsing(fn ($record) => $record->barang->sum('stok'))
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->suffix(' unit'),

                Tables\Columns\TextColumn::make('barang_count')
                    ->label('Jenis Barang')
                    ->counts('barang')
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->suffix(' jenis'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_barang')
                    ->label('Memiliki Barang')
                    ->query(fn ($query) => $query->has('barang'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Jenis Barang')
                    ->modalDescription('Apakah Anda yakin ingin menghapus jenis barang ini? Semua barang yang terkait akan terpengaruh.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Jenis Barang Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus jenis barang yang dipilih?'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisBarangs::route('/'),
            'create' => Pages\CreateJenisBarang::route('/create'),
            'edit' => Pages\EditJenisBarang::route('/{record}/edit'),
        ];
    }
}
