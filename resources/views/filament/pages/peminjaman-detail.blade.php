<div class="space-y-6">
    <!-- Kode Transaksi -->
    @if($record->kode_transaksi)
    <div class="fi-section rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Kode Transaksi</h3>
            <div class="inline-flex items-center px-4 py-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                <span class="text-xl font-mono font-bold text-primary-600 dark:text-primary-400">{{ $record->kode_transaksi }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Informasi Peminjam -->
    <div class="fi-section rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
            <x-heroicon-m-user class="w-5 h-5 inline mr-2"/>
            Informasi Peminjam
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $peminjam?->nama ?? 'Data tidak tersedia' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $peminjam?->email ?? 'Data tidak tersedia' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $peminjam?->telepon ?? 'Data tidak tersedia' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $peminjam?->alamat ?? 'Data tidak tersedia' }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Peminjaman -->
    <div class="fi-section rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
            <x-heroicon-m-calendar-days class="w-5 h-5 inline mr-2"/>
            Detail Peminjaman
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pinjam</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $record->tanggal_pinjam?->format('d/m/Y') ?? 'Data tidak tersedia' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kembali</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $record->tanggal_kembali?->format('d/m/Y') ?? 'Data tidak tersedia' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durasi</label>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $record->lama_hari ?? 0 }} hari</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 {{ $record->status === 'selesai' ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400' : 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400' }}">
                    @if($record->status === 'selesai')
                        <x-heroicon-m-check-circle class="w-3 h-3 mr-1"/>
                    @else
                        <x-heroicon-m-clock class="w-3 h-3 mr-1"/>
                    @endif
                    {{ ucfirst($record->status) }}
                </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                <div class="mt-1 inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                    @switch($record->pembayaran)
                        @case('cash')
                            <x-heroicon-m-banknotes class="w-3 h-3 mr-1"/>
                            Cash
                            @break
                        @case('transfer')
                            <x-heroicon-m-building-library class="w-3 h-3 mr-1"/>
                            Transfer Bank
                            @break
                        @case('ewallet')
                            <x-heroicon-m-device-phone-mobile class="w-3 h-3 mr-1"/>
                            E-Wallet
                            @break
                        @default
                            {{ ucfirst($record->pembayaran) }}
                    @endswitch
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Harga</label>
                <p class="mt-1 text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($record->total_harga, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Diskon Info (jika ada) -->
        @if($record->kode_diskon && $record->nominal_diskon > 0)
        <div class="mt-4 p-3 bg-success-50 dark:bg-success-500/10 rounded-lg border border-success-200 dark:border-success-500/20">
            <div class="flex items-center">
                <x-heroicon-m-gift class="w-4 h-4 text-success-600 dark:text-success-400 mr-2"/>
                <span class="text-sm font-medium text-success-800 dark:text-success-300">
                    Diskon {{ $record->kode_diskon }}: -Rp {{ number_format($record->nominal_diskon, 0, ',', '.') }}
                </span>
            </div>
        </div>
        @endif
    </div>

    <!-- Daftar Barang -->
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                <x-heroicon-m-cube class="w-5 h-5 inline mr-2"/>
                Daftar Barang yang Dipinjam
            </h3>
            @if($details && $details->count() > 0)
                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga/Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($details as $detail)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                                <x-heroicon-m-cube class="w-4 h-4 text-primary-600 dark:text-primary-400"/>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $detail->barang?->nama ?? 'Barang tidak ditemukan' }}
                                            </p>
                                            @if($detail->barang?->jenisBarang)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $detail->barang->jenisBarang->nama }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->harga, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $detail->jumlah }} unit
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                    Total:
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                        Rp {{ number_format($record->total_harga, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-cube class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3"/>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data barang yang dipinjam.</p>
                </div>
            @endif
        </div>
    </div>
</div>
