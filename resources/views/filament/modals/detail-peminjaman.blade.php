<div class="space-y-6" x-data="{}" x-init="$nextTick(() => { $el.closest('.fi-modal-content')?.scrollTo({top: 0, behavior: 'instant'}) })">
    <!-- Info Peminjaman -->
    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Peminjaman</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-600">Peminjam:</span>
                <span class="ml-2 text-gray-900">{{ $peminjaman->peminjam->nama }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Status:</span>
                <span class="ml-2 px-2 py-1 rounded text-xs font-medium
                    {{ $peminjaman->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $peminjaman->status == 'selesai' ? 'Selesai' : 'Belum Dikembalikan' }}
                </span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Tanggal Pinjam:</span>
                <span class="ml-2 text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Tanggal Kembali:</span>
                <span class="ml-2 text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Detail Barang -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang yang Dipinjam</h3>
        
        @forelse ($peminjaman->detail as $detail)
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-900">{{ $detail->barang->nama }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $detail->barang->jenis->nama }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $detail->jumlah }} unit
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <div class="flex flex-col items-center">
                    <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-4h-3l-3.5-3.5a1.5 1.5 0 00-2.12 0L7 9h-3"></path>
                    </svg>
                    <span class="text-sm">Tidak ada detail barang</span>
                </div>
            </div>
        @endforelse
        
        <!-- Summary -->
        @if($peminjaman->detail->count() > 0)
            <div class="mt-4 bg-blue-50 px-4 py-3 rounded-lg border border-blue-200">
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-blue-800">
                        Total {{ $peminjaman->detail->count() }} jenis barang
                    </span>
                    <span class="font-medium text-blue-800">
                        Durasi: {{ $peminjaman->lama_hari }} hari
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>
