<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Pengguna Sistem';
    
    protected static ?string $modelLabel = 'Pengguna';
    
    protected static ?string $pluralModelLabel = 'Pengguna Sistem';
    
    protected static ?string $navigationGroup = 'Manajemen User';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Administrator',
                                'user' => 'User',
                            ])
                            ->required()
                            ->default('user'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Keamanan')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Terverifikasi')
                            ->displayFormat('d/m/Y H:i'),
                        Forms\Components\DateTimePicker::make('last_login_at')
                            ->label('Login Terakhir')
                            ->displayFormat('d/m/Y H:i')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('name')
                //     ->label('Nama')
                //     ->searchable()
                //     ->sortable()
                //     ->weight('semibold'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'user' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'admin' => 'heroicon-o-shield-check',
                        'user' => 'heroicon-o-user',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrator',
                        'user' => 'User',
                        default => ucfirst($state),
                    }),
                    
                Tables\Columns\TextColumn::make('online_status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if (!$record->last_login_at) {
                            return 'offline';
                        }
                        
                        $lastLogin = Carbon::parse($record->last_login_at);
                        $now = Carbon::now();
                        
                        // Online jika login dalam 5 menit terakhir
                        if ($lastLogin->diffInMinutes($now) <= 5) {
                            return 'online';
                        }
                        
                        return 'offline';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'offline' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'online' => 'heroicon-o-signal',
                        'offline' => 'heroicon-o-signal-slash',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'online' => 'Online',
                        'offline' => 'Offline',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Login Terakhir')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Belum pernah login')
                    ->icon('heroicon-m-clock')
                    ->since()
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (!$state) return null;
                        
                        $lastLogin = Carbon::parse($state);
                        $now = Carbon::now();
                        $diffInMinutes = $lastLogin->diffInMinutes($now);
                        
                        if ($diffInMinutes <= 5) {
                            return "Sedang online - Login {$diffInMinutes} menit yang lalu";
                        }
                        
                        return $state->format('d F Y, H:i:s');
                    })
                    ->description(function ($record): ?string {
                        if (!$record->last_login_at) return null;
                        
                        $lastLogin = Carbon::parse($record->last_login_at);
                        $now = Carbon::now();
                        $diffInMinutes = $lastLogin->diffInMinutes($now);
                        
                        if ($diffInMinutes <= 1) {
                            return '🟢 Baru saja online';
                        } elseif ($diffInMinutes <= 5) {
                            return '🟡 Sedang online';
                        } elseif ($diffInMinutes <= 30) {
                            return '🟠 Baru offline';
                        }
                        
                        return null;
                    }),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Belum verifikasi')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Administrator',
                        'user' => 'User',
                    ]),
                    
                Tables\Filters\Filter::make('online')
                    ->label('Sedang Online')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('last_login_at', '>=', Carbon::now()->subMinutes(5))
                    ),
                    
                Tables\Filters\Filter::make('verified')
                    ->label('Email Terverifikasi')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                    
                Tables\Filters\Filter::make('never_logged_in')
                    ->label('Belum Pernah Login')
                    ->query(fn (Builder $query): Builder => $query->whereNull('last_login_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
