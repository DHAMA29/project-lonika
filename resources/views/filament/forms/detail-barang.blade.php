@if($peminjaman && $peminjaman->detail->count() > 0)
    <div class="space-y-2" 
         x-data="{}" 
         x-init="setTimeout(() => { 
             const modal = document.querySelector('.fi-modal-content');
             if (modal) modal.scrollTop = 0;
         }, 100)">
        @foreach ($peminjaman->detail as $detail)
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $detail->barang->nama }}</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $detail->barang->jenis->nama }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $detail->jumlah }} unit
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Summary -->
        <div class="mt-3 bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-md border border-blue-200 dark:border-blue-800">
            <div class="flex justify-between items-center text-xs">
                <span class="font-medium text-blue-800 dark:text-blue-200">
                    {{ $peminjaman->detail->count() }} jenis barang
                </span>
                <span class="font-medium text-blue-800 dark:text-blue-200">
                    {{ $peminjaman->lama_hari }} hari
                </span>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-4 text-gray-500 dark:text-gray-400"
         x-data="{}" 
         x-init="setTimeout(() => { 
             const modal = document.querySelector('.fi-modal-content');
             if (modal) modal.scrollTop = 0;
         }, 100)">
        <div class="flex flex-col items-center">
            <svg class="h-6 w-6 text-gray-400 dark:text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-4h-3l-3.5-3.5a1.5 1.5 0 00-2.12 0L7 9h-3"></path>
            </svg>
            <span class="text-xs">Tidak ada detail barang</span>
        </div>
    </div>
@endif
