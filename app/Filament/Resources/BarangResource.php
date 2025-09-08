<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $pluralModelLabel = 'Barang';
    protected static ?string $modelLabel = 'Barang';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Barang')
                    ->schema([
                        Forms\Components\Select::make('jenis_id')
                            ->label('Jenis Barang')
                            ->relationship('jenis', 'nama')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama barang'),

                        Forms\Components\TextInput::make('stok')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->suffix('unit')
                            ->helperText(fn ($record) => $record ? self::getStokHelperText($record) : null),

                        Forms\Components\TextInput::make('harga_hari')
                            ->label('Harga per Hari')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->placeholder('0'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Gambar Barang')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Barang')
                            ->image()
                            ->directory('barang')
                            ->imagePreviewHeight('200')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('450')
                            ->maxSize(2048),
                    ]),

                Forms\Components\Section::make('Informasi Stok & Peminjaman')
                    ->schema([
                        Forms\Components\View::make('filament.forms.barang-peminjaman-info')
                            ->viewData(fn ($record) => ['barang' => $record])
                    ])
                    ->hiddenOn(['create'])
                    ->visible(fn ($record) => $record && self::hasActiveBorrowings($record)),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
             //   Tables\Columns\ImageColumn::make('foto')
               //     ->label('Foto')
                 //   ->circular()
                   // ->size(60),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('jenis.nama')
                    ->label('Jenis')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('stok')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->stok > 10 ? 'success' : ($record->stok > 0 ? 'warning' : 'danger')),

                Tables\Columns\TextColumn::make('harga_hari')
                    ->label('Harga/Hari')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->relationship('jenis', 'nama')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('stok_rendah')
                    ->label('Stok Rendah')
                    ->query(fn ($query) => $query->where('stok', '<=', 5))
                    ->toggle(),

                Tables\Filters\Filter::make('stok_habis')
                    ->label('Stok Habis')
                    ->query(fn ($query) => $query->where('stok', 0))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }

    protected static function getStokHelperText($record): ?string
    {
        if (!$record) return null;

        // Hitung stok yang sedang dipinjam
        $stokDipinjam = \App\Models\DetailPeminjaman::whereHas('peminjaman', function ($query) {
            $query->where('status', 'belum dikembalikan');
        })
        ->where('barang_id', $record->id)
        ->sum('jumlah');

        if ($stokDipinjam > 0) {
            $stokTersedia = $record->stok;
            return "⚠️ {$stokDipinjam} unit sedang dipinjam. Stok tersedia: {$stokTersedia} unit. Total keseluruhan: " . ($stokTersedia + $stokDipinjam) . " unit";
        }

        return "✅ Semua stok tersedia (tidak ada yang sedang dipinjam)";
    }

    protected static function hasActiveBorrowings($record): bool
    {
        if (!$record) return false;

        return \App\Models\DetailPeminjaman::whereHas('peminjaman', function ($query) {
            $query->where('status', 'belum dikembalikan');
        })
        ->where('barang_id', $record->id)
        ->exists();
    }
}
