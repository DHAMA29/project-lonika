<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Models\Peminjaman;
use App\Models\Peminjam;
use App\Models\Barang;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?string $modelLabel = 'Transaksi';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peminjam')
                    ->schema([
                        Forms\Components\Select::make('peminjam_id')
                            ->label('Peminjam')
                            ->relationship('peminjam', 'nama')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('telepon')
                                    ->tel()
                                    ->required(),
                                Forms\Components\Textarea::make('alamat')
                                    ->required(),
                            ])
                            ->required(),
                    ]),

                Forms\Components\Section::make('Detail Peminjaman')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_pinjam')
                            ->label('Tanggal Pinjam')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                $tanggalKembali = $get('tanggal_kembali');
                                if ($state && $tanggalKembali) {
                                    $tanggalPinjam = \Carbon\Carbon::parse($state);
                                    $tanggalKembaliCarbon = \Carbon\Carbon::parse($tanggalKembali);
                                    // Include both start and end date in calculation
                                    $diff = $tanggalPinjam->diffInDays($tanggalKembaliCarbon) + 1;
                                    $set('lama_hari', max(1, (int) $diff));
                                    
                                    // Trigger total calculation
                                    static::calculateTotal($set, $get);
                                }
                            }),
                        
                        Forms\Components\DatePicker::make('tanggal_kembali')
                            ->label('Tanggal Kembali')
                            ->required()
                            ->after('tanggal_pinjam')
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                $tanggalPinjam = $get('tanggal_pinjam');
                                if ($state && $tanggalPinjam) {
                                    $tanggalPinjamCarbon = \Carbon\Carbon::parse($tanggalPinjam);
                                    $tanggalKembali = \Carbon\Carbon::parse($state);
                                    // Include both start and end date in calculation
                                    $diff = $tanggalPinjamCarbon->diffInDays($tanggalKembali) + 1;
                                    $set('lama_hari', max(1, (int) $diff));
                                    
                                    // Trigger total calculation
                                    static::calculateTotal($set, $get);
                                }
                            }),
                        
                        Forms\Components\TextInput::make('lama_hari')
                            ->label('Lama Hari')
                            ->integer()
                            ->minValue(1)
                            ->suffix('hari')
                            ->default(1)
                            ->required()
                            ->live(onBlur: true)
                            ->rules(['integer', 'min:1'])
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                // Ensure integer value
                                $lamaDays = max(1, (int) ($state ?: 1));
                                $set('lama_hari', $lamaDays);
                                
                                // Recalculate all subtotals when duration changes
                                $items = $get('detail_peminjaman') ?? [];
                                $updatedItems = [];
                                
                                foreach ($items as $index => $item) {
                                    if (isset($item['harga']) && isset($item['jumlah'])) {
                                        $harga = floatval($item['harga']);
                                        $jumlah = intval($item['jumlah']);
                                        $subtotal = $harga * $jumlah * $lamaDays;
                                        $item['subtotal'] = $subtotal;
                                    }
                                    $updatedItems[] = $item;
                                }
                                
                                $set('detail_peminjaman', $updatedItems);
                                
                                // Force recalculate total
                                static::calculateTotal($set, $get);
                            }),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Daftar Barang yang Dipinjam')
                    ->schema([
                        Forms\Components\Repeater::make('detail_peminjaman')
                            ->label('Barang')
                            ->relationship('detail')
                            ->schema([
                                Forms\Components\Select::make('barang_id')
                                    ->label('Barang')
                                    ->options(function () {
                                        return Barang::all()->mapWithKeys(function ($barang) {
                                            return [$barang->id => "{$barang->nama} (Stok: {$barang->stok}) - Rp " . number_format($barang->harga_hari, 0, ',', '.')];
                                        });
                                    })
                                    ->searchable()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                        if ($state) {
                                            $barang = Barang::find($state);
                                            if ($barang) {
                                                // Set harga dengan nilai valid
                                                $set('harga', $barang->harga_hari);
                                                
                                                // Pastikan jumlah minimal 1
                                                $jumlah = $get('jumlah') ?: 1;
                                                $set('jumlah', $jumlah);
                                                
                                                // Calculate subtotal dengan durasi
                                                $lamaDays = $get('../../lama_hari') ?: 1;
                                                $subtotal = $barang->harga_hari * $jumlah * $lamaDays;
                                                $set('subtotal', $subtotal);
                                                
                                                // Trigger total calculation immediately
                                                static::calculateTotal($set, $get);
                                            }
                                        } else {
                                            // Set default values when no barang selected
                                            $set('harga', 0);
                                            $set('subtotal', 0);
                                            static::calculateTotal($set, $get);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('jumlah')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                        $harga = $get('harga') ?: 0;
                                        $jumlah = $state ?: 1;
                                        
                                        // Ensure jumlah is set properly
                                        $set('jumlah', $jumlah);
                                        
                                        if ($harga > 0) {
                                            $lamaDays = $get('../../lama_hari') ?: 1;
                                            $subtotal = $harga * $jumlah * $lamaDays;
                                            $set('subtotal', $subtotal);
                                            
                                            // Trigger total calculation immediately
                                            static::calculateTotal($set, $get);
                                        }
                                    })
                                    ->rule('min:1'),
                                
                                Forms\Components\TextInput::make('harga')
                                    ->label('Harga/Unit')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->default(0)
                                    ->required()
                                    ->dehydrated(true),
                                
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->default(0)
                                    ->required()
                                    ->dehydrated(true),
                            ])
                            ->columns(4)
                            ->minItems(1)
                            ->addActionLabel('Tambah Barang')
                            ->deleteAction(
                                fn ($action) => $action->after(function (callable $set, callable $get) {
                                    static::calculateTotal($set, $get);
                                })
                            )
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(1)
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                // Force calculate total whenever repeater changes
                                static::calculateTotal($set, $get);
                            })
                            ->addAction(
                                fn ($action) => $action->after(function (callable $set, callable $get) {
                                    static::calculateTotal($set, $get);
                                })
                            )
                    ]),

                Forms\Components\Section::make('Pembayaran & Status')
                    ->schema([
                        Forms\Components\Select::make('pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer Bank',
                                'ewallet' => 'E-Wallet',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'belum dikembalikan' => 'Belum Dikembalikan',
                                'selesai' => 'Selesai',
                            ])
                            ->default('belum dikembalikan')
                            ->required()
                            ->native(false)
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                self::calculateTotal($set, $get);
                            }),
                        
                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(true)
                            ->live(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detail Barang yang Dipinjam')
                    ->schema([
                        Forms\Components\View::make('filament.forms.detail-barang')
                            ->viewData(fn ($record) => ['peminjaman' => $record])
                    ])
                    ->hiddenOn(['create', 'edit'])
                    ->visible(fn ($record) => $record && $record->detail()->count() > 0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peminjam.nama')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tgl Pinjam')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Tgl Kembali')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'belum dikembalikan',
                        'success' => 'selesai',
                    ]),
                
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->weight(FontWeight::Medium),
                
                Tables\Columns\TextColumn::make('lama_hari')
                    ->label('Durasi')
                    ->suffix(' hari')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('pembayaran')
                    ->label('Pembayaran')
                    ->badge()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'belum dikembalikan' => 'Belum Dikembalikan',
                        'selesai' => 'Selesai',
                    ]),
                
                Tables\Filters\SelectFilter::make('pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer Bank',
                        'ewallet' => 'E-Wallet',
                    ]),
                
                Tables\Filters\Filter::make('tanggal_pinjam')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query) => $query->whereDate('tanggal_pinjam', '>=', $data['from']))
                            ->when($data['until'], fn ($query) => $query->whereDate('tanggal_pinjam', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detail Transaksi')
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make(),
               // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function calculateTotal(callable $set, callable $get): void
    {
        $items = $get('detail_peminjaman') ?? [];
        $lamaDays = $get('lama_hari') ?: 1;
        $total = 0;

        foreach ($items as $item) {
            if (isset($item['harga'], $item['jumlah'])) {
                $harga = floatval($item['harga'] ?? 0);
                $jumlah = intval($item['jumlah'] ?? 0);
                
                if ($harga > 0 && $jumlah > 0) {
                    $itemTotal = $harga * $jumlah * $lamaDays;
                    $total += $itemTotal;
                }
            }
        }

        $set('total_harga', $total);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjaman::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
