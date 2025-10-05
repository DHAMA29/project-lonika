<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiskonResource\Pages;
use App\Filament\Resources\DiskonResource\RelationManagers;
use App\Models\DiskonModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiskonResource extends Resource
{
    protected static ?string $model = DiskonModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Diskon';
    protected static ?string $pluralModelLabel = 'Diskon';
    protected static ?string $modelLabel = 'Diskon';
    protected static ?string $navigationGroup = 'Manajemen Promosi';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('jenis_kode')
                                    ->label('Jenis Kode')
                                    ->required()
                                    ->options([
                                        'manual' => 'Manual',
                                        'acak' => 'Acak/Otomatis'
                                    ])
                                    ->default('manual')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state === 'acak') {
                                            // Generate random 6-character code
                                            do {
                                                $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
                                            } while (DiskonModel::where('kode_diskon', $code)->exists());
                                            
                                            $set('kode_diskon', $code);
                                        } else {
                                            $set('kode_diskon', '');
                                        }
                                    }),
                                    
                                Forms\Components\TextInput::make('kode_diskon')
                                    ->label('Kode Diskon')
                                    ->required()
                                    ->maxLength(6)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Contoh: PROMO5')
                                    ->disabled(fn (Forms\Get $get): bool => $get('jenis_kode') === 'acak')
                                    ->dehydrated(),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('persentase')
                                    ->label('Persentase Diskon')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->default(10),
                                    
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->required()
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'nonaktif' => 'Non-aktif'
                                    ])
                                    ->default('aktif'),
                            ]),
                            
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->maxLength(255)
                            ->rows(2)
                            ->placeholder('Deskripsi singkat tentang diskon (opsional)'),
                    ]),
                    
                Forms\Components\Section::make('Pengaturan Lanjutan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('batas_penggunaan')
                                    ->label('Batas Penggunaan')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Kosongkan untuk unlimited')
                                    ->helperText('Jumlah maksimal penggunaan kode diskon'),
                                    
                                Forms\Components\TextInput::make('jumlah_terpakai')
                                    ->label('Jumlah Terpakai')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Otomatis bertambah saat digunakan'),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('tanggal_mulai')
                                    ->label('Tanggal Mulai')
                                    ->placeholder('Kosongkan untuk langsung aktif')
                                    ->helperText('Kapan diskon mulai berlaku'),
                                    
                                Forms\Components\DateTimePicker::make('tanggal_berakhir')
                                    ->label('Tanggal Berakhir')
                                    ->placeholder('Kosongkan untuk tidak ada batas')
                                    ->helperText('Kapan diskon berakhir'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_diskon')
                    ->label('Kode Diskon')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('persentase')
                    ->label('Diskon')
                    ->suffix('%')
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'aktif',
                        'heroicon-o-x-circle' => 'nonaktif',
                    ]),
                    
                Tables\Columns\BadgeColumn::make('jenis_kode')
                    ->label('Jenis')
                    ->colors([
                        'warning' => 'manual',
                        'info' => 'acak',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'manual' => 'Manual',
                        'acak' => 'Otomatis',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('usage_info')
                    ->label('Penggunaan')
                    ->getStateUsing(function ($record) {
                        $used = $record->jumlah_terpakai ?? 0;
                        $limit = $record->batas_penggunaan;
                        
                        if ($limit) {
                            return "{$used}/{$limit}";
                        }
                        
                        return "{$used}/∞";
                    })
                    ->badge()
                    ->color(function ($record) {
                        $used = $record->jumlah_terpakai ?? 0;
                        $limit = $record->batas_penggunaan;
                        
                        if (!$limit) return 'gray';
                        
                        $percentage = ($used / $limit) * 100;
                        return $percentage >= 80 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success');
                    }),
                    
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Berakhir')
                    ->date('d/m/Y')
                    ->placeholder('Tidak ada batas')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-aktif'
                    ]),
                    
                Tables\Filters\SelectFilter::make('jenis_kode')
                    ->label('Jenis Kode')
                    ->options([
                        'manual' => 'Manual',
                        'acak' => 'Otomatis'
                    ]),
            ])
            ->actions([
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
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiskons::route('/'),
            'create' => Pages\CreateDiskon::route('/create'),
            'edit' => Pages\EditDiskon::route('/{record}/edit'),
        ];
    }
}
