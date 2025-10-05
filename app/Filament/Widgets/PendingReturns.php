<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingReturns extends BaseWidget
{
    protected static ?string $heading = 'Transaksi Perlu Tindak Lanjut';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $pollingInterval = '120s';

    protected function getTableQuery(): Builder
    {
        return Peminjaman::query()
            ->where('status', 'belum dikembalikan')
            ->with(['peminjam', 'detail.barang'])
            ->orderBy('tanggal_kembali', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            // Status Visual Indicator  
            Tables\Columns\IconColumn::make('rental_status')
                ->label('')
                ->icon(function ($record) {
                    $today = \Carbon\Carbon::today();
                    $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
                    
                    if ($tanggalPinjam->gt($today)) {
                        return 'heroicon-s-calendar'; // Booking masa depan
                    } elseif ($tanggalPinjam->eq($today)) {
                        return 'heroicon-s-play-circle'; // Berlangsung hari ini
                    } else {
                        return 'heroicon-s-clock'; // Berlangsung dari kemarin
                    }
                })
                ->color(function ($record) {
                    $today = \Carbon\Carbon::today();
                    $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
                    
                    if ($tanggalPinjam->gt($today)) {
                        return 'info'; // Biru untuk booking
                    } elseif ($tanggalPinjam->eq($today)) {
                        return 'success'; // Hijau untuk hari ini
                    } else {
                        return 'warning'; // Kuning untuk ongoing
                    }
                })
                ->tooltip(function ($record) {
                    $today = \Carbon\Carbon::today();
                    $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
                    
                    if ($tanggalPinjam->gt($today)) {
                        return 'Booking - Belum dimulai';
                    } elseif ($tanggalPinjam->eq($today)) {
                        return 'Berlangsung hari ini';
                    } else {
                        return 'Berlangsung dari kemarin';
                    }
                }),
                
            Tables\Columns\TextColumn::make('kode_transaksi')
                ->label('Kode')
                ->searchable()
                ->copyable(),
                
            Tables\Columns\TextColumn::make('peminjam.nama')
                ->label('Peminjam')
                ->searchable()
                ->description(fn ($record) => $record->peminjam?->telepon),
                
            Tables\Columns\TextColumn::make('tanggal_pinjam')
                ->label('Mulai Sewa')
                ->date('d M Y')
                ->sortable()
                ->description(function ($record) {
                    $today = \Carbon\Carbon::today();
                    $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
                    
                    if ($tanggalPinjam->gt($today)) {
                        return 'Booking - Dimulai ' . $tanggalPinjam->diffForHumans();
                    } elseif ($tanggalPinjam->eq($today)) {
                        return 'Berlangsung hari ini';
                    } else {
                        return 'Berlangsung sejak ' . $tanggalPinjam->diffForHumans();
                    }
                })
                ->color(function ($record) {
                    $today = \Carbon\Carbon::today();
                    $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
                    
                    if ($tanggalPinjam->gt($today)) {
                        return 'info'; // Biru untuk booking
                    } elseif ($tanggalPinjam->eq($today)) {
                        return 'success'; // Hijau untuk hari ini
                    } else {
                        return 'warning'; // Kuning untuk ongoing
                    }
                }),
                
            Tables\Columns\TextColumn::make('tanggal_kembali')
                ->label('Jatuh Tempo')
                ->date('d M Y')
                ->sortable()
                ->color(function ($record) {
                    $today = Carbon::today();
                    $dueDate = Carbon::parse($record->tanggal_kembali);
                    
                    if ($dueDate->lt($today)) {
                        return 'danger'; // Terlambat
                    } elseif ($dueDate->eq($today)) {
                        return 'warning'; // Hari ini
                    } elseif ($dueDate->lte($today->copy()->addDays(2))) {
                        return 'info'; // 2 hari lagi
                    }
                    return 'gray';
                })
                ->badge()
                ->description(function ($record) {
                    $today = Carbon::today();
                    $dueDate = Carbon::parse($record->tanggal_kembali);
                    
                    if ($dueDate->lt($today)) {
                        $days = $today->diffInDays($dueDate);
                        return "Terlambat {$days} hari";
                    } elseif ($dueDate->eq($today)) {
                        return "Jatuh tempo hari ini";
                    } elseif ($dueDate->gt($today)) {
                        $days = $today->diffInDays($dueDate);
                        return "Jatuh tempo {$days} hari lagi";
                    }
                    return null;
                }),
                
            Tables\Columns\TextColumn::make('total_harga')
                ->label('Total')
                ->money('IDR'),
                
            Tables\Columns\TextColumn::make('detail_count')
                ->label('Items')
                ->counts('detail')
                ->alignCenter()
                ->badge()
                ->color('info'),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('mark_returned')
                ->label('Tandai Selesai')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->action(function ($record) {
                    $record->update(['status' => 'selesai']);
                    
                    $stockService = app(\App\Services\StockAvailabilityService::class);
                    $stockService->returnStock($record->id);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Transaksi berhasil diselesaikan')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pengembalian')
                ->modalDescription('Apakah semua barang sudah dikembalikan?')
                ->visible(function ($record) {
                    $today = \Carbon\Carbon::today();
                    $tanggalPinjam = \Carbon\Carbon::parse($record->tanggal_pinjam)->startOfDay();
                    
                    return $tanggalPinjam->lte($today) && $record->status === 'belum dikembalikan';
                }),
                
            Tables\Actions\Action::make('view')
                ->label('Detail')
                ->icon('heroicon-m-eye')
                ->url(fn ($record) => route('filament.admin.resources.peminjamen.edit', $record))
                ->openUrlInNewTab(),
        ];
    }
    
    public function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 20];
    }
    
    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('rental_status')
                ->label('Status Rental')
                ->options([
                    'booking' => '📅 Booking (belum dimulai)',
                    'active_today' => '🟢 Berlangsung hari ini',
                    'active_ongoing' => '🟡 Berlangsung dari kemarin',
                ])
                ->query(function (Builder $query, array $data) {
                    $today = Carbon::today();
                    
                    return match ($data['value'] ?? null) {
                        'booking' => $query->where('tanggal_pinjam', '>', $today),
                        'active_today' => $query->whereDate('tanggal_pinjam', $today),
                        'active_ongoing' => $query->where('tanggal_pinjam', '<', $today),
                        default => $query,
                    };
                }),
                
            Tables\Filters\SelectFilter::make('status_tempo')
                ->label('Status Tempo')
                ->options([
                    'overdue' => 'Terlambat',
                    'today' => 'Jatuh Tempo Hari Ini',
                    'soon' => 'Jatuh Tempo Segera (2 hari)',
                ])
                ->query(function (Builder $query, array $data) {
                    $today = Carbon::today();
                    
                    return match ($data['value'] ?? null) {
                        'overdue' => $query->where('tanggal_kembali', '<', $today),
                        'today' => $query->whereDate('tanggal_kembali', $today),
                        'soon' => $query->whereBetween('tanggal_kembali', [$today->copy()->addDay(), $today->copy()->addDays(2)]),
                        default => $query,
                    };
                }),
        ];
    }
}
