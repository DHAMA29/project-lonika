@extends('layouts.marketplace')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="h2 text-success">Pesanan Berhasil!</h1>
                <p class="lead text-muted">Terima kasih! Pesanan Anda telah berhasil diproses.</p>
            </div>
            
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt"></i> Detail Pesanan #{{ $peminjaman->id }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Kode Transaksi Section (Prominent Display) -->
                    <div class="alert alert-success border-0 mb-4" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <h6 class="alert-heading mb-3">
                                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                    <strong>Kode Transaksi Anda</strong>
                                </h6>
                                
                                <!-- Professional Transaction Code Display -->
                                <div class="transaction-code-container mb-3">
                                    <div class="card border-0 shadow-sm d-inline-block">
                                        <div class="card-body p-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="me-3">
                                                    <i class="bi bi-receipt-cutoff text-primary" style="font-size: 2rem;"></i>
                                                </div>
                                                <div class="text-center">
                                                    <small class="text-muted d-block mb-1">TRANSACTION CODE</small>
                                                    <span class="badge bg-dark fs-4 px-4 py-2" style="font-family: 'Courier New', monospace; letter-spacing: 2px; font-weight: bold;">
                                                        {{ $peminjaman->kode_transaksi }}
                                                    </span>
                                                </div>
                                                <div class="ms-3">
                                                    <button class="btn btn-outline-primary btn-sm" onclick="copyTransactionCode('{{ $peminjaman->kode_transaksi }}')" title="Salin kode transaksi">
                                                        <i class="bi bi-clipboard me-1"></i>Salin
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Simpan kode ini dengan aman untuk melacak pesanan Anda
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Data Peminjam:</h6>
                            <p class="mb-1"><strong>{{ $peminjaman->peminjam->nama }}</strong></p>
                            <p class="mb-1">{{ $peminjaman->peminjam->email }}</p>
                            <p class="mb-1">{{ $peminjaman->peminjam->telepon }}</p>
                            <p class="mb-0">{{ $peminjaman->peminjam->alamat }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Periode Peminjaman:</h6>
                            <p class="mb-1"><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Kembali:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Durasi:</strong> {{ $peminjaman->lama_hari }} hari</p>
                            <p class="mb-0"><strong>Pembayaran:</strong> 
                                <span class="badge bg-info">{{ ucfirst($peminjaman->pembayaran) }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <h6>Barang yang Dipinjam:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Harga/Hari</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman->detail as $detail)
                                <tr>
                                    <td>{{ $detail->barang->nama }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>Rp {{ number_format($peminjaman->total_harga, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle"></i> Informasi Penting:</h6>
                        <ul class="mb-0">
                            <li>Silakan hubungi kami untuk mengatur pengambilan barang</li>
                            <li>Barang harus dikembalikan tepat waktu sesuai tanggal yang disepakati</li>
                            <li>Simpan kode transaksi <strong>{{ $peminjaman->kode_transaksi }}</strong> untuk referensi</li>
                            <li>Pembayaran harus dilunasi sebelum barang diserahkan</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Kembali ke Beranda
                        </a>
                        <a href="{{ route('peminjaman.orders') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Lihat Semua Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.transaction-code-container {
    position: relative;
}

.transaction-code-container::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(0,123,255,0.1) 0%, transparent 70%);
    border-radius: 15px;
    z-index: -1;
}

.transaction-code-container .card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.transaction-code-container .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.transaction-code-container .badge {
    position: relative;
    overflow: hidden;
}

.transaction-code-container .badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.transaction-code-container .badge:hover::before {
    left: 100%;
}

@media (max-width: 768px) {
    .transaction-code-container .badge {
        font-size: 1.1rem !important;
        padding: 0.5rem 1rem !important;
        letter-spacing: 1px !important;
    }
    
    .transaction-code-container .card-body {
        padding: 1rem !important;
    }
    
    .transaction-code-container .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<script>
function copyTransactionCode(code) {
    if (navigator.clipboard && window.isSecureContext) {
        // Modern browsers
        navigator.clipboard.writeText(code).then(function() {
            showCopySuccess();
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
            showCopySuccess();
        } else {
            showCopyError();
        }
    } catch (err) {
        showCopyError();
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess() {
    // Create temporary success message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Berhasil!</strong> Kode transaksi telah disalin ke clipboard.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

function showCopyError() {
    // Create temporary error message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Gagal menyalin!</strong> Silakan salin kode secara manual.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endsection
