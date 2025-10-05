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
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $pluralModelLabel = 'Pelanggan';
    protected static ?string $modelLabel = 'Pelanggan';
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'peminjam', column: 'nama', ignoreRecord: true)
                    ->placeholder('Masukkan nama pelanggan/perusahaan')
                    ->helperText('Nama harus unik dalam sistem'),
                
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan email'),
                
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->unique(table: 'peminjam', column: 'telepon', ignoreRecord: true)
                    ->placeholder('Masukkan nomor telepon')
                    ->helperText('Nomor telepon harus unik dalam sistem'),
                
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
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Nomor HP')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->alamat;
                    })
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('peminjaman_count')
                    ->label('Total Transaksi')
                    ->counts('peminjaman')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\Filter::make('active_customers')
                    ->label('Pelanggan Aktif')
                    ->query(fn ($query) => $query->has('peminjaman'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
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
