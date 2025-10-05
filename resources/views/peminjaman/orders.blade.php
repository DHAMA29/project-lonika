@extends('layouts.marketplace')

@section('content')
<style>
/* Orders Page Responsive Styles */
@media (max-width: 768px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card-body {
        padding: 2rem 1rem !important;
    }
    
    .display-5 {
        font-size: 1.75rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .input-group-lg .form-control {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }
    
    .input-group-lg .input-group-text {
        padding: 0.75rem 1rem;
    }
    
    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1.5rem 0.75rem !important;
    }
    
    .display-5 {
        font-size: 1.5rem;
    }
    
    .display-5 i {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .lead {
        font-size: 0.95rem;
        text-align: left;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .input-group-text {
        border-radius: 0.5rem !important;
        margin-bottom: 0.5rem;
        justify-content: center;
    }
    
    .input-group .form-control {
        border-radius: 0.5rem !important;
        margin-bottom: 0.5rem;
    }
    
    .input-group .btn {
        border-radius: 0.5rem !important;
        width: 100%;
    }
    
    .col-md-8 {
        padding-left: 0;
        padding-right: 0;
    }
}

/* Order cards responsive */
.order-card {
    transition: transform 0.2s ease;
}

.order-card:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .order-card .card-body {
        padding: 1rem;
    }
    
    .order-card .row {
        text-align: center;
    }
    
    .order-card .col-md-3 {
        margin-bottom: 1rem;
    }
    
    .order-card h6 {
        font-size: 0.9rem;
    }
    
    .order-card .badge {
        display: inline-block;
        margin: 0.25rem 0;
    }
}

@media (max-width: 576px) {
    .order-card .card-body {
        padding: 0.75rem;
    }
    
    .order-card .fs-6 {
        font-size: 0.85rem !important;
    }
    
    .order-card .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .order-card .btn-sm {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
    }
}

/* Empty state responsive */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

@media (max-width: 576px) {
    .empty-state {
        padding: 2rem 0.5rem;
    }
    
    .empty-state i {
        font-size: 3rem !important;
    }
    
    .empty-state h4 {
        font-size: 1.25rem;
    }
    
    .empty-state p {
        font-size: 0.9rem;
    }
}

/* Search form responsive */
.search-form input:focus {
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

@media (max-width: 576px) {
    .search-form {
        margin-bottom: 1rem;
    }
}
</style>

<div class="container py-4 py-md-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-3 p-md-5">
                    <div class="text-center mb-5">
                        <h2 class="display-5 fw-bold text-primary mb-3">
                            <i class="fas fa-search me-3"></i>
                            Cari Pesanan
                        </h2>
                        <p class="lead text-muted">
                            Masukkan nama peminjam atau kode transaksi untuk melihat riwayat pesanan
                        </p>
                    </div>
                    
                    <!-- Form Pencarian -->
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8">
                            <form method="GET" action="{{ route('peminjaman.orders') }}" class="search-form">
                                <div class="input-group input-group-lg mb-4">
                                    <span class="input-group-text bg-primary text-white border-primary">
                                        <i class="fas fa-search text-white"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-primary" 
                                           name="search" 
                                           id="search_input"
                                           placeholder="Masukkan nama peminjam atau kode transaksi" 
                                           value="{{ $search_query ?? '' }}"
                                           autocomplete="off"
                                           spellcheck="false"
                                           autocorrect="off"
                                           autocapitalize="off"
                                           required>
                                    <button class="btn btn-primary border-primary" type="submit">
                                        <i class="fas fa-search me-2 text-white"></i>
                                        <span class="text-white">Cari Pesanan</span>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-database text-primary fs-3 mb-2"></i>
                                        <h6>Database Lengkap</h6>
                                        <small class="text-muted">Semua data pesanan tersimpan dengan aman</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-search text-success fs-3 mb-2"></i>
                                        <h6>Pencarian Cerdas</h6>
                                        <small class="text-muted">Cari berdasarkan nama atau kode transaksi</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-history text-info fs-3 mb-2"></i>
                                        <h6>Riwayat Lengkap</h6>
                                        <small class="text-muted">Lihat semua transaksi dan status peminjaman</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-shield-alt text-warning fs-3 mb-2"></i>
                                        <h6>Aman & Terpercaya</h6>
                                        <small class="text-muted">Sistem tracking yang aman dan reliable</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil Pencarian -->
    @if(isset($peminjaman) && $peminjaman->isNotEmpty())
    <div class="container mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list-alt me-2"></i>
                                Hasil Pencarian - {{ $peminjaman->count() }} pesanan ditemukan
                                @if($search_query)
                                    untuk "{{ $search_query }}"
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($peminjaman as $order)
                            <div class="order-card card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-1">
                                                <i class="fas fa-user me-2"></i>
                                                {{ $order->peminjam->nama ?? 'Nama tidak tersedia' }}
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-2"></i>{{ $order->peminjam->telepon ?? 'Tidak tersedia' }}
                                                <span class="ms-3">
                                                    <i class="fas fa-envelope me-2"></i>{{ $order->peminjam->email ?? 'Tidak tersedia' }}
                                                </span>
                                            </small>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <small class="text-muted d-block">Kode Transaksi</small>
                                                <span class="badge bg-dark fs-6 px-3 py-2" style="font-family: 'Courier New', monospace; letter-spacing: 1px;">
                                                    {{ $order->kode_transaksi ?? 'Tidak tersedia' }}
                                                </span>
                                                @if($order->kode_transaksi)
                                                <button class="btn btn-sm btn-outline-secondary ms-1" onclick="copyCode('{{ $order->kode_transaksi }}')" title="Salin kode">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <span class="badge bg-{{ $order->status == 'belum dikembalikan' ? 'warning' : ($order->status == 'selesai' ? 'success' : 'primary') }} fs-6">
                                                {{ $order->status == 'belum dikembalikan' ? 'Belum Dikembalikan' : ucfirst($order->status) }}
                                            </span>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $order->tanggal_pinjam ? \Carbon\Carbon::parse($order->tanggal_pinjam)->format('d M Y') : 'Tanggal tidak tersedia' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>ID Pesanan:</strong><br>
                                <span class="text-primary">#{{ $order->id }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Kode Transaksi:</strong><br>
                                <span class="text-dark fw-bold" style="font-family: 'Courier New', monospace;">
                                    {{ $order->kode_transaksi ?? 'Belum tersedia' }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Tanggal Kembali:</strong><br>
                                {{ $order->tanggal_kembali ? \Carbon\Carbon::parse($order->tanggal_kembali)->format('d M Y') : 'Belum ditentukan' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Total Item:</strong><br>
                                {{ $order->detail->count() ?? 0 }} jenis barang
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Total Harga:</strong><br>
                                <span class="text-success fw-bold">Rp {{ number_format($order->total_harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Lama Peminjaman:</strong><br>
                                {{ $order->lama_hari ?? 0 }} hari
                            </div>
                            <div class="col-md-3">
                                <strong>Metode Pembayaran:</strong><br>
                                {{ ucfirst($order->pembayaran ?? 'Tidak tersedia') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Total Jumlah:</strong><br>
                                {{ $order->detail->sum('jumlah') ?? 0 }} unit
                            </div>
                        </div>
                        
                        <h6><i class="fas fa-box me-2"></i>Detail Barang:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->detail as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->nama ?? 'Nama barang tidak tersedia' }}</td>
                                        <td>{{ $detail->barang->jenisBarang->nama ?? 'Kategori tidak tersedia' }}</td>
                                        <td>{{ $detail->jumlah ?? 0 }}</td>
                                        <td>Rp {{ number_format($detail->harga ?? $detail->barang->harga_hari ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->subtotal ?? (($detail->jumlah ?? 0) * ($detail->harga ?? $detail->barang->harga_hari ?? 0)), 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada detail barang yang tersedia</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @elseif(isset($search_query))
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-search-minus text-muted fs-1 mb-3"></i>
                <h4>Tidak Ditemukan Pesanan</h4>
                <p class="text-muted">Tidak ada pesanan yang ditemukan dengan pencarian "{{ $search_query }}"</p>
                <div class="mt-4">
                    <small class="text-muted">
                        Pastikan ejaan nama atau kode sudah benar, atau coba gunakan kata kunci yang lebih singkat
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.search-form input:focus {
    box-shadow: none;
    border-color: #dee2e6;
}

.order-card {
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.input-group-lg .form-control {
    font-size: 1.1rem;
}

.badge {
    font-weight: 500;
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 2rem;
    }
    
    .card-body.p-5 {
        padding: 2rem !important;
    }
}
</style>

<script>
// Prevent search input history/autocomplete
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        // Clear input on page load if not from search
        if (!searchInput.value || searchInput.value.trim() === '') {
            searchInput.value = '';
        }
        
        // Prevent browser autocomplete
        searchInput.setAttribute('data-lpignore', 'true');
        searchInput.setAttribute('data-form-type', 'other');
    }
});

// Function to copy transaction code
function copyCode(code) {
    if (navigator.clipboard && window.isSecureContext) {
        // Modern browsers
        navigator.clipboard.writeText(code).then(function() {
            showCopyNotification('Kode transaksi berhasil disalin: ' + code, 'success');
        }).catch(function() {
            fallbackCopyTextToClipboard(code);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyTextToClipboard(code);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyNotification('Kode transaksi berhasil disalin: ' + text, 'success');
        } else {
            showCopyNotification('Gagal menyalin kode transaksi', 'error');
        }
    } catch (err) {
        showCopyNotification('Gagal menyalin kode transaksi', 'error');
    }
    
    document.body.removeChild(textArea);
}

function showCopyNotification(message, type) {
    // Remove existing notification if any
    const existingAlert = document.querySelector('.copy-notification');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create new notification
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + (type === 'success' ? 'success' : 'warning') + ' alert-dismissible fade show position-fixed copy-notification';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.style.maxWidth = '400px';
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    const title = type === 'success' ? 'Berhasil!' : 'Gagal!';
    
    alertDiv.innerHTML = `
        <i class="fas ${icon} me-2"></i>
        <strong>${title}</strong> ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 4000);
}
</script>
@endsection
