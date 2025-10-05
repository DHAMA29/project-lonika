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
    protected static ?string $navigationGroup = 'Manajemen Inventory';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->description('Data utama barang yang akan disewakan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama barang'),

                                Forms\Components\Select::make('jenis_barang_id')
                                    ->label('Jenis Barang')
                                    ->relationship('jenisBarang', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama')
                                            ->label('Nama Jenis Barang')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->placeholder('Deskripsi singkat tentang barang')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->compact(),

                Forms\Components\Section::make('Stok & Harga')
                    ->description('Informasi ketersediaan dan tarif sewa')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('stok')
                                    ->label('Jumlah Stok')
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
                                    ->placeholder('0')
                                    ->helperText('Tarif sewa per hari'),
                            ]),
                    ])
                    ->compact(),

                Forms\Components\Section::make('Gambar Barang')
                    ->description('Upload foto produk (rasio 4:3, maksimal 15MB)')
                    ->schema([
                        Forms\Components\FileUpload::make('gambar')
                            ->label('Foto Barang')
                            ->image()
                            ->disk('public')
                            ->directory('barang')
                            ->visibility('public')
                            ->imagePreviewHeight('250')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('900')
                            ->maxSize(15360)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                    ])
                    ->compact()
                    ->collapsed(),

                Forms\Components\Section::make('Informasi Stok & Peminjaman')
                    ->schema([
                        Forms\Components\View::make('filament.forms.barang-peminjaman-info')
                            ->viewData(fn ($record) => ['barang' => $record])
                    ])
                    ->hiddenOn(['create'])
                    ->visible(fn ($record) => $record && self::hasActiveBorrowings($record))
                    ->compact(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn ($record) => $record->jenisBarang?->nama)
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->stok > 10 ? 'success' : ($record->stok > 0 ? 'warning' : 'danger'))
                    ->formatStateUsing(fn ($state) => $state . ' unit'),

                Tables\Columns\TextColumn::make('harga_hari')
                    ->label('Harga/Hari')
                    ->money('IDR')
                    ->sortable()
                    ->weight(FontWeight::Medium),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->stok > 0)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_barang')
                    ->label('Filter Jenis')
                    ->relationship('jenisBarang', 'nama')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada barang')
            ->emptyStateDescription('Mulai dengan menambahkan barang pertama ke inventory.')
            ->emptyStateIcon('heroicon-o-camera')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'view' => Pages\ViewBarang::route('/{record}'),
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
