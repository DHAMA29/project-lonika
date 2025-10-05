@if($peminjaman && $peminjaman->detail->count() > 0)
    <div class="space-y-2" 
         x-data="{}" 
         x-init="setTimeout(() => { 
             const modal = document.querySelector('.fi-modal-content');
             if (modal) modal.scrollTop = 0;
         }, 100)">
        @foreach ($peminjaman->detail as $detail)
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                <x-heroicon-m-cube class="w-4 h-4 text-primary-600 dark:text-primary-400"/>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $detail->barang?->nama ?? 'Barang tidak tersedia' }}</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $detail->barang?->jenisBarang?->nama ?? 'Jenis tidak tersedia' }}</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($detail->harga ?? 0, 0, ',', '.') }}/hari
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">•</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Subtotal: Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            {{ $detail->jumlah }} unit
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Summary -->
        <div class="mt-3 bg-primary-50 dark:bg-primary-900/20 px-4 py-3 rounded-lg border border-primary-200 dark:border-primary-800">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <x-heroicon-m-cube class="w-4 h-4 text-primary-600 dark:text-primary-400"/>
                    <span class="text-sm font-medium text-primary-800 dark:text-primary-300">
                        {{ $peminjaman->detail->count() }} jenis barang
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <x-heroicon-m-calendar-days class="w-4 h-4 text-primary-600 dark:text-primary-400"/>
                    <span class="text-sm font-medium text-primary-800 dark:text-primary-300">
                        {{ $peminjaman->lama_hari }} hari
                    </span>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-primary-200 dark:border-primary-700">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-primary-700 dark:text-primary-400">Total Harga:</span>
                    <span class="text-lg font-bold text-primary-800 dark:text-primary-300">
                        Rp {{ number_format($peminjaman->total_harga ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-8 text-gray-500 dark:text-gray-400"
         x-data="{}" 
         x-init="setTimeout(() => { 
             const modal = document.querySelector('.fi-modal-content');
             if (modal) modal.scrollTop = 0;
         }, 100)">
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mb-3">
                <x-heroicon-o-cube class="h-6 w-6 text-gray-400 dark:text-gray-500"/>
            </div>
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tidak ada detail barang</span>
            <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">Belum ada barang yang dipinjam</span>
        </div>
    </div>
@endif
