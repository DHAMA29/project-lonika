<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

/**
 * Resource untuk manajemen transaksi peminjaman barang
 * 
 * Mengelola seluruh proses peminjaman dari pembuatan transaksi,
 * tracking status, hingga pengembalian barang
 */
class PeminjamanResource extends Resource
{
    // Model Configuration
    protected static ?string $model = Peminjaman::class;
    
    // Navigation Configuration  
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    protected static ?int $navigationSort = 2;
    
    // Resource Labels
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?string $modelLabel = 'Transaksi';

    /**
     * Form konfigurasi untuk pembuatan dan editing transaksi peminjaman
     * 
     * Mencakup informasi peminjam, detail peminjaman dengan perhitungan otomatis,
     * daftar barang yang dipinjam, dan informasi tambahan seperti diskon
     */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Informasi Peminjam Section
                Forms\Components\Section::make('Informasi Peminjam')
                    ->description('Pilih peminjam atau tambah peminjam baru')
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
                    ])
                    ->collapsible()
                    ->columns(1),

                // Detail Peminjaman Section  
                Forms\Components\Section::make('Detail Peminjaman')
                    ->description('Atur tanggal pinjam dan kembali dengan perhitungan otomatis')
                    ->schema([
                        Forms\Components\Grid::make(3)
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
                                            // Calculate actual difference without adding 1
                                            $diff = $tanggalPinjam->diffInDays($tanggalKembaliCarbon);
                                            // Ensure minimum 1 day for same day rental
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
                                            // Calculate actual difference without adding 1
                                            $diff = $tanggalPinjamCarbon->diffInDays($tanggalKembali);
                                            // Ensure minimum 1 day for same day rental
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
                            ]),
                    ])
                    ->collapsible()
                    ->columns(1),

                // Daftar Barang Section
                Forms\Components\Section::make('Daftar Barang yang Dipinjam')
                    ->description('Pilih barang yang akan dipinjam dengan jumlah dan harga')
                    ->schema([
                        Forms\Components\Repeater::make('detail_peminjaman')
                            ->label('Barang')
                            ->relationship('detail')
                            ->schema([
                                Forms\Components\Grid::make(4)
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
                                            ->live()
                                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                if ($state) {
                                                    $barang = Barang::find($state);
                                                    if ($barang) {
                                                        $set('harga', $barang->harga_hari);
                                                        $jumlah = $get('jumlah') ?: 1;
                                                        $set('jumlah', $jumlah);
                                                        
                                                        $lamaDays = $get('../../lama_hari') ?: 1;
                                                        $subtotal = $barang->harga_hari * $jumlah * $lamaDays;
                                                        $set('subtotal', $subtotal);
                                                        
                                                        static::calculateTotal($set, $get);
                                                    }
                                                } else {
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
                                            ->live()
                                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                $harga = $get('harga') ?: 0;
                                                $jumlah = $state ?: 1;
                                                $set('jumlah', $jumlah);
                                                
                                                if ($harga > 0) {
                                                    $lamaDays = $get('../../lama_hari') ?: 1;
                                                    $subtotal = $harga * $jumlah * $lamaDays;
                                                    $set('subtotal', $subtotal);
                                                    
                                                    static::calculateTotal($set, $get);
                                                }
                                            }),
                                        
                                        Forms\Components\TextInput::make('harga')
                                            ->label('Harga/Unit')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->default(0)
                                            ->dehydrated(true),
                                        
                                        Forms\Components\TextInput::make('subtotal')
                                            ->label('Subtotal')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->default(0)
                                            ->dehydrated(true),
                                    ]),
                            ])
                            ->minItems(1)
                            ->addActionLabel('Tambah Barang')
                            ->deleteAction(
                                fn ($action) => $action->after(fn (callable $set, callable $get) => static::calculateTotal($set, $get))
                            )
                            ->addAction(
                                fn ($action) => $action->after(fn (callable $set, callable $get) => static::calculateTotal($set, $get))
                            )
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(1)
                            ->live()
                            ->afterStateUpdated(fn (callable $set, callable $get) => static::calculateTotal($set, $get)),
                    ])
                    ->collapsible()
                    ->columns(1),

                // Pembayaran & Status Section
                Forms\Components\Section::make('Pembayaran & Status')
                    ->description('Atur metode pembayaran dan status transaksi')
                    ->schema([
                        Forms\Components\Grid::make(2)
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
                                    ->label('Status')
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
                            ]),
                        
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
                    ->collapsible()
                    ->columns(1),
            ]);
    }

    /**
     * Konfigurasi tabel untuk menampilkan daftar transaksi peminjaman
     * 
     * Menampilkan informasi pelanggan, tanggal sewa, status, dan total harga
     * dengan kemampuan filter dan search yang optimal
     */
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Load relationships
                $query = $query->with(['peminjam', 'detail.barang']);
                
                // Prioritaskan sorting berdasarkan status rental
                $today = \Carbon\Carbon::today()->format('Y-m-d');
                
                return $query->selectRaw('
                    *,
                    CASE 
                        WHEN status = "belum dikembalikan" AND DATE(tanggal_pinjam) = ? THEN 1
                        WHEN status = "belum dikembalikan" AND DATE(tanggal_pinjam) < ? AND DATE(tanggal_kembali) >= ? THEN 2
                        WHEN status = "belum dikembalikan" AND DATE(tanggal_pinjam) > ? THEN 3
                        WHEN status = "selesai" THEN 4
                        ELSE 5
                    END as rental_priority
                ', [$today, $today, $today, $today])
                ->orderBy('rental_priority', 'asc')
                ->orderBy('tanggal_pinjam', 'desc');
            })
            ->columns([
                // Status Visual Indicator
                Tables\Columns\IconColumn::make('rental_status_indicator')
                    ->label('')
                    ->icon(fn ($record) => match(self::getRentalStatus($record)) {
                        'active_today' => 'heroicon-s-play-circle',
                        'active_ongoing' => 'heroicon-s-clock', 
                        'booking_future' => 'heroicon-s-calendar',
                        'completed' => 'heroicon-s-check-circle',
                        default => 'heroicon-o-question-mark-circle'
                    })
                    ->color(fn ($record) => match(self::getRentalStatus($record)) {
                        'active_today' => 'success',
                        'active_ongoing' => 'warning',
                        'booking_future' => 'info',
                        'completed' => 'gray',
                        default => 'gray'
                    })
                    ->size('lg')
                    ->tooltip(fn ($record) => match(self::getRentalStatus($record)) {
                        'active_today' => 'Sedang berlangsung hari ini',
                        'active_ongoing' => 'Sedang berlangsung dari kemarin',
                        'booking_future' => 'Booking untuk masa mendatang',
                        'completed' => 'Transaksi selesai',
                        default => 'Status tidak diketahui'
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('peminjam.nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->default('Data tidak tersedia')
                    ->color(function ($record) {
                        return $record->peminjam ? null : 'danger';
                    }),
                
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Sewa')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => match(self::getRentalStatus($record)) {
                        'active_today' => 'success',
                        'active_ongoing' => 'warning', 
                        'booking_future' => 'info',
                        default => null
                    })
                    ->weight(fn ($record) => match(self::getRentalStatus($record)) {
                        'active_today' => 'bold',
                        'active_ongoing' => 'medium',
                        default => 'normal'
                    }),
                
                // Status Rental yang Lebih Detail
                Tables\Columns\BadgeColumn::make('rental_status')
                    ->label('Status Rental')
                    ->getStateUsing(fn ($record) => self::getRentalStatus($record))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active_today' => 'Berlangsung Hari Ini',
                        'active_ongoing' => 'Berlangsung (dari kemarin)',
                        'booking_future' => 'Booking Mendatang',
                        'completed' => 'Selesai',
                        default => 'Status Tidak Diketahui'
                    })
                    ->colors([
                        'success' => 'active_today',
                        'warning' => 'active_ongoing', 
                        'info' => 'booking_future',
                        'gray' => 'completed',
                    ])
                    ->icons([
                        'active_today' => 'heroicon-s-play-circle',
                        'active_ongoing' => 'heroicon-s-clock',
                        'booking_future' => 'heroicon-s-calendar',
                        'completed' => 'heroicon-s-check-circle',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Transaksi')
                    ->colors([
                        'warning' => 'belum dikembalikan',
                        'success' => 'selesai',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'belum dikembalikan' => 'Aktif',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->weight('medium'),
                
                Tables\Columns\TextColumn::make('lama_hari')
                    ->label('Durasi')
                    ->suffix(' hari')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Transaksi')
                    ->options([
                        'belum dikembalikan' => 'Sedang Berlangsung',
                        'selesai' => 'Selesai',
                    ]),
                
                // Filter berdasarkan Status Rental Detail
                Tables\Filters\SelectFilter::make('rental_status')
                    ->label('Status Rental Detail')
                    ->options([
                        'active_today' => '🟢 Berlangsung Hari Ini',
                        'active_ongoing' => '🟡 Berlangsung (dari kemarin)', 
                        'booking_future' => '🔵 Booking Mendatang',
                        'completed' => '⚪ Selesai',
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['value']) {
                            return $query;
                        }
                        
                        $today = \Carbon\Carbon::today();
                        
                        return $query->where(function ($query) use ($data, $today) {
                            switch ($data['value']) {
                                case 'active_today':
                                    $query->where('status', 'belum dikembalikan')
                                          ->whereDate('tanggal_pinjam', $today->format('Y-m-d'));
                                    break;
                                    
                                case 'active_ongoing':
                                    $query->where('status', 'belum dikembalikan')
                                          ->whereDate('tanggal_pinjam', '<', $today->format('Y-m-d'))
                                          ->whereDate('tanggal_kembali', '>=', $today->format('Y-m-d'));
                                    break;
                                    
                                case 'booking_future':
                                    $query->where('status', 'belum dikembalikan')
                                          ->whereDate('tanggal_pinjam', '>', $today->format('Y-m-d'));
                                    break;
                                    
                                case 'completed':
                                    $query->where('status', 'selesai');
                                    break;
                            }
                        });
                    }),
                
                Tables\Filters\Filter::make('tanggal_pinjam')
                    ->label('Periode Sewa')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query) => $query->whereDate('tanggal_pinjam', '>=', $data['from']))
                            ->when($data['until'], fn ($query) => $query->whereDate('tanggal_pinjam', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail'),
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
            ->striped()
            ->paginated([10, 25, 50]);
    }

    /**
     * Menghitung total harga berdasarkan detail peminjaman
     * 
     * Melakukan kalkulasi otomatis berdasarkan harga barang × jumlah × lama hari
     * dan mengupdate field total_harga secara real-time
     */
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

    /**
     * Menentukan status rental berdasarkan tanggal dan status transaksi
     * 
     * @param mixed $record Record peminjaman
     * @return string Status: active_today, active_ongoing, booking_future, completed
     */
    private static function getRentalStatus($record): string
    {
        // Jika transaksi sudah selesai
        if ($record->status === 'selesai') {
            return 'completed';
        }
        
        $today = \Carbon\Carbon::today();
        $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
        $tanggalKembali = \Carbon\Carbon::parse($record->tanggal_kembali)->startOfDay();
        
        // Transaksi aktif (belum dikembalikan)
        if ($record->status === 'belum dikembalikan') {
            // Rental dimulai hari ini
            if ($tanggalPinjam->equalTo($today)) {
                return 'active_today';
            }
            
            // Rental sudah dimulai dari kemarin atau sebelumnya
            if ($tanggalPinjam->lessThan($today) && $tanggalKembali->greaterThanOrEqualTo($today)) {
                return 'active_ongoing';
            }
            
            // Booking untuk masa depan (belum dimulai)
            if ($tanggalPinjam->greaterThan($today)) {
                return 'booking_future';
            }
        }
        
        return 'completed'; // fallback
    }

    /**
     * Konfigurasi halaman-halaman yang tersedia untuk resource
     * 
     * Menyediakan halaman list, create, view, dan edit untuk manajemen transaksi
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjaman::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'view' => Pages\ViewPeminjaman::route('/{record}'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
