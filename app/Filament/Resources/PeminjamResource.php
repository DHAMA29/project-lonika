<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamResource\Pages;
use App\Models\Peminjam;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;

class PeminjamResource extends Resource
{
    protected static ?string $model = Peminjam::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Daftar/History Peminjam';
    protected static ?string $pluralModelLabel = 'Daftar/History Peminjam';
    protected static ?string $modelLabel = 'Peminjam';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama peminjam'),
                
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan email'),
                
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->placeholder('Masukkan nomor telepon'),
                
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->maxLength(500)
                    ->placeholder('Masukkan alamat lengkap'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Peminjam')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(60)
                    ->tooltip(function ($record) {
                        return $record->alamat;
                    })
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Nomor HP')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                
                Tables\Columns\TextColumn::make('peminjaman_count')
                    ->label('Jumlah Transaksi')
                    ->counts('peminjaman')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_peminjaman')
                    ->label('Pernah Meminjam')
                    ->query(fn ($query) => $query->has('peminjaman'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            //Tables\Actions\EditAction::make(),
              //  Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjams::route('/'),
            'create' => Pages\CreatePeminjam::route('/create'),
            'edit' => Pages\EditPeminjam::route('/{record}/edit'),
        ];
    }
}
