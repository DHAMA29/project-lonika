@if($barang)
    @php
        $aktivePeminjaman = \App\Models\DetailPeminjaman::with(['peminjaman.peminjam', 'peminjaman'])
            ->whereHas('peminjaman', function ($query) {
                $query->where('status', 'belum dikembalikan');
            })
            ->where('barang_id', $barang->id)
            ->get();
        
        $totalDipinjam = $aktivePeminjaman->sum('jumlah');
    @endphp

    @if($aktivePeminjaman->count() > 0)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        Peringatan: {{ $totalDipinjam }} unit sedang dipinjam
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p class="mb-2">Hati-hati saat mengubah stok. Berikut detail peminjaman aktif:</p>
                        <div class="space-y-2">
                            @foreach($aktivePeminjaman as $detail)
                                <div class="bg-white dark:bg-gray-800 rounded p-2 border border-yellow-300 dark:border-yellow-700">
                                    <div class="flex justify-between items-start text-xs">
                                        <div>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $detail->peminjaman->peminjam->nama }}</span>
                                            <div class="text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->format('d M Y') }} - 
                                                {{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_kembali)->format('d M Y') }}
                                            </div>
                                        </div>
                                        <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ $detail->jumlah }} unit
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-3 p-2 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                        <div class="text-xs text-blue-800 dark:text-blue-200">
                            <strong>Ringkasan Stok:</strong><br>
                            • Stok tersedia: {{ $barang->stok }} unit<br>
                            • Sedang dipinjam: {{ $totalDipinjam }} unit<br>
                            • Total keseluruhan: {{ $barang->stok + $totalDipinjam }} unit
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
