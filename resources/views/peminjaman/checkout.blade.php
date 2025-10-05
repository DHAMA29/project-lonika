@extends('layouts.marketplace')

@section('content')
<style>
/* Checkout Page Responsive Styles */
@media (max-width: 991px) {
    .position-sticky {
        position: relative !important;
        top: auto !important;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
}

@media (max-width: 768px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn-group .btn {
        width: auto;
        margin-bottom: 0;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        border-radius: 0.375rem !important;
        margin-bottom: 0.5rem;
    }
    
    .input-group .btn {
        border-radius: 0.375rem !important;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .card-header h5 {
        font-size: 1rem;
    }
    
    .card-header small {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.75rem;
    }
    
    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .form-control {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }
    
    .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    .alert {
        font-size: 0.85rem;
        padding: 0.75rem;
    }
}

/* Autocomplete responsive */
.autocomplete-container {
    position: relative;
}

.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .autocomplete-suggestions {
        max-height: 150px;
        font-size: 0.9rem;
    }
}

/* Date picker responsive */
.date-input-group {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 576px) {
    .date-input-group {
        flex-direction: column;
        gap: 0.75rem;
    }
}

/* Summary card responsive */
.order-summary-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

@media (max-width: 576px) {
    .order-summary-item {
        padding: 0.5rem 0;
    }
    
    .order-summary-item img {
        width: 60px;
        height: 60px;
    }
    
    .order-summary-item h6 {
        font-size: 0.9rem;
    }
    
    .order-summary-item small {
        font-size: 0.75rem;
    }
}

/* Modal responsive */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-content {
        border-radius: 0.5rem;
    }
    
    .modal-header {
        padding: 1rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        padding: 1rem;
    }
}

/* Form validation responsive */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    font-size: 0.875rem;
}

@media (max-width: 576px) {
    .invalid-feedback {
        font-size: 0.8rem;
    }
}
</style>
<style>
/* Autocomplete Styles */
.autocomplete-container {
    position: relative;
}

.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.autocomplete-item {
    padding: 10px 14px;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.2s ease;
}

.autocomplete-item:last-child {
    border-bottom: none;
}

.autocomplete-item:hover {
    background-color: #f8f9fa;
}

.autocomplete-item.selected {
    background-color: #e7f3ff;
}

.autocomplete-name {
    font-weight: 500;
    color: #495057;
    margin-bottom: 3px;
}

.autocomplete-details {
    font-size: 11px;
    color: #6c757d;
    line-height: 1.3;
}

.autocomplete-icon {
    color: #198754;
    margin-right: 6px;
}

/* Loading indicator */
.autocomplete-loading {
    padding: 10px 14px;
    text-align: center;
    color: #6c757d;
    font-style: italic;
    font-size: 13px;
}

/* Form field highlight when auto-filled - softer styling */
.form-control.auto-filled {
    background-color: #f8fff8;
    border-color: #198754;
    transition: all 0.3s ease;
    box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.1);
}

.form-control.auto-filled:focus {
    background-color: white;
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

/* Clear button for autocomplete */
.autocomplete-clear {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #adb5bd;
    cursor: pointer;
    padding: 4px;
    border-radius: 3px;
    display: none;
    font-size: 14px;
}

.autocomplete-clear:hover {
    background-color: #f8f9fa;
    color: #6c757d;
}

.autocomplete-container.has-value .autocomplete-clear {
    display: block;
}

/* Softer card styling for data peminjam */
.card {
    border: 1px solid #e9ecef;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

/* Subtle placeholder styling */
.form-control::placeholder {
    color: #adb5bd;
    font-style: italic;
}

/* Softer alert styling for kebijakan pengembalian */
.alert-policy {
    background-color: #fefefe;
    border: 1px solid #e9ecef;
    border-left: 3px solid #ffc107;
    color: #495057;
    padding: 12px 16px;
    margin-top: 12px;
}

.alert-policy .text-warning {
    color: #856404 !important;
}

.alert-policy h6 {
    color: #495057;
    font-weight: 500;
}

.alert-policy .small {
    color: #6c757d;
}

/* Date warning styling */
.date-warning {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    margin-top: 0.5rem;
    border-left: 3px solid #ffc107;
    background-color: #fff3cd;
    border-color: #ffecb5;
    color: #664d03;
}

.date-warning i {
    color: #f57c00;
}

.alert-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
}

/* Date input styling when invalid */
.form-control.date-invalid {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
</style>

<div class="container py-5">
    <h2 class="mb-4">
        <i class="bi bi-credit-card"></i> Checkout
    </h2>
    
    @if($errors->any())
    <div class="alert alert-danger">
        <h6 class="alert-heading mb-2">
            <i class="bi bi-exclamation-triangle me-2"></i>Perhatian!
        </h6>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @if($errors->has('general'))
        <hr class="my-3">
        <div class="small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Tips:</strong> Pastikan data nama dan nomor telepon yang Anda masukkan benar dan sesuai.
        </div>
        @endif
    </div>
    @endif
    
    <form method="POST" action="{{ route('peminjaman.checkout.process') }}">
        @csrf
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <!-- Data Peminjam -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person-fill me-2"></i>Data Peminjam
                            <small class="text-muted ms-2 d-none d-md-inline">(Ketik nama untuk melihat history)</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap *</label>
                                <div class="autocomplete-container">
                                    <input type="text" 
                                           class="form-control" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama') }}" 
                                           autocomplete="off"
                                           spellcheck="false"
                                           autocorrect="off"
                                           autocapitalize="off"
                                           data-lpignore="true"
                                           data-form-type="other"
                                           placeholder="Budi Santoso"
                                           required>
                                    <button type="button" class="autocomplete-clear" onclick="clearAutocomplete()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="autocomplete-suggestions" id="autocompleteSuggestions"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="budi@gmail.com" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="telepon" class="form-label">No. Telepon *</label>
                                <input type="tel" class="form-control" id="telepon" name="telepon" value="{{ old('telepon') }}" placeholder="08123456789" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap *</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Jl. Merdeka No. 123, Jakarta Pusat" required>{{ old('alamat') }}</textarea>
                        </div>
                        
                        <!-- Info auto-fill -->
                        <div class="alert alert-info d-none" id="autoFillInfo">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Data berhasil diisi otomatis!</strong> Silakan periksa dan ubah jika diperlukan.
                        </div>
                    </div>
                </div>
                
                <!-- Periode Peminjaman -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Periode Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pinjam" class="form-label">Tanggal Pakai *</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" 
                                       value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}" 
                                       min="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_pinjam" class="form-label">Jam Pakai *</label>
                                <input type="time" class="form-control" id="jam_pinjam" name="jam_pinjam" 
                                       value="{{ old('jam_pinjam', now()->format('H:i')) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali *</label>
                                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" 
                                       value="{{ old('tanggal_kembali') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_kembali" class="form-label">Jam Kembali *</label>
                                <input type="time" class="form-control" id="jam_kembali" name="jam_kembali" 
                                       value="{{ old('jam_kembali') }}" required>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock me-3 text-primary fs-5"></i>
                                <div class="flex-grow-1">
                                    <strong>Durasi: <span id="durasi">-</span></strong>
                                    <div class="small text-muted mt-1">
                                        <span id="durasi-detail">Pilih tanggal dan jam untuk melihat durasi</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Warning Keterlambatan -->
                            <div class="alert-policy" id="warning-denda" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle me-3 text-warning mt-1" style="color: #856404 !important;"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2">Kebijakan Pengembalian</h6>
                                        <div class="small">
                                            <div class="mb-1">• Barang harus dikembalikan tepat waktu</div>
                                            <div class="mb-1">• Keterlambatan 1 jam akan dikenakan biaya tambahan 1 hari penuh</div>
                                            <div>• Berlaku kelipatan untuk setiap hari berikutnya</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#lateFeeModal" style="color: #856404; text-decoration: none;">
                                        <small>Detail Kebijakan</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Metode Pembayaran -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Metode Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pembayaran" id="cash" value="cash" {{ old('pembayaran') == 'cash' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cash">
                                        <i class="bi bi-cash-coin text-success"></i> Cash
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pembayaran" id="transfer" value="transfer" {{ old('pembayaran') == 'transfer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="transfer">
                                        <i class="bi bi-bank text-primary"></i> Transfer Bank
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pembayaran" id="ewallet" value="ewallet" {{ old('pembayaran') == 'ewallet' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ewallet">
                                        <i class="bi bi-phone text-info"></i> E-Wallet
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Kanan: Rincian Pesanan -->
            <div class="col-lg-4 col-md-12">
                <!-- Availability Warnings Container -->
                <div id="checkout-availability-warnings" class="mb-4">
                    <!-- Availability warning messages will be inserted here -->
                </div>
                
                <!-- Ringkasan Pesanan -->
                <div class="card position-sticky" style="top: 100px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cart-check me-2"></i>Rincian Pesanan
                        </h5>
                        <small class="opacity-75">{{ count($cartItems) }} item dalam keranjang</small>
                    </div>
                    <div class="card-body">
                        @php
                            $subtotal = 0;
                        @endphp
                        
                        <!-- Item List -->
                        @foreach($cartItems as $item)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">{{ $item['nama'] }}</div>
                                    <div class="small text-muted mb-1">
                                        <i class="bi bi-tag me-1"></i>{{ $item['kode'] ?? 'N/A' }}
                                    </div>
                                    <div class="d-flex align-items-center small text-muted">
                                        <span class="me-3">
                                            <i class="bi bi-box me-1"></i>{{ $item['quantity'] }} unit
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            Rp {{ number_format($item['harga'], 0, ',', '.') }}/hari
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end ms-3">
                                    <div class="fw-bold text-primary">
                                        Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted">/hari</small>
                                </div>
                            </div>
                        </div>
                        @php
                            $subtotal += $item['harga'] * $item['quantity'];
                        @endphp
                        @endforeach
                        
                        <hr>
                        
                        <!-- Kode Diskon -->
                        <div class="mb-3">
                            <label for="kode_diskon" class="form-label">Kode Diskon (Opsional)</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="kode_diskon" 
                                       name="kode_diskon" 
                                       placeholder="Masukkan kode diskon" 
                                       value="{{ old('kode_diskon') }}" 
                                       maxlength="6" 
                                       style="text-transform: uppercase;"
                                       autocomplete="off"
                                       autocorrect="off"
                                       autocapitalize="off"
                                       spellcheck="false"
                                       data-lpignore="true"
                                       data-form-type="other">
                                <button class="btn btn-outline-primary" type="button" id="btn-apply-discount">
                                    <i class="bi bi-check-circle me-1"></i>Terapkan
                                </button>
                            </div>
                            <div id="discount-feedback" class="mt-1"></div>
                        </div>
                        
                        <!-- Kalkulasi Total -->
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal per hari:</span>
                                <strong class="text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Durasi sewa:</span>
                                <strong class="text-dark">
                                    <span id="durasi-display">
                                        <i class="bi bi-calendar-event me-1"></i>Pilih tanggal
                                    </span>
                                </strong>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2" id="billing-info" style="display: none;">
                                <span class="text-warning">
                                    <i class="bi bi-clock me-1"></i>Billing:
                                </span>
                                <strong class="text-warning"><span id="billing-days">-</span> hari</strong>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2" id="discount-info" style="display: none;">
                                <span class="text-success">
                                    <i class="bi bi-percent me-1"></i>Diskon (<span id="discount-percent">0</span>%):
                                </span>
                                <strong class="text-success">-Rp <span id="discount-amount">0</span></strong>
                            </div>
                            
                            <hr class="my-2">
                            
                            <div class="d-flex justify-content-between">
                                <span class="h5 mb-0 text-dark">Total Pembayaran:</span>
                                <span class="h4 mb-0 text-primary fw-bold" id="total-harga">
                                    <i class="bi bi-currency-dollar me-1"></i>Rp 0
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Proses Pesanan
                                <small class="d-block opacity-75">Konfirmasi dan kirim pesanan</small>
                            </button>
                            <a href="{{ route('peminjaman.cart') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Keranjang
                            </a>
                        </div>
                        
                        <!-- Security Info -->
                        <div class="mt-3 p-2 bg-light rounded text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Transaksi Anda aman dan terenkripsi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('peminjaman.late-fee-info')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const subtotal = {{ $subtotal }};
    let autocompleteTimeout;
    let selectedIndex = -1;
    let currentData = [];
    
    // === AUTOCOMPLETE FUNCTIONALITY ===
    function initAutocomplete() {
        const namaInput = $('#nama');
        const suggestionsContainer = $('#autocompleteSuggestions');
        const autocompleteContainer = $('.autocomplete-container');
        
        // Input event for searching
        namaInput.on('input', function() {
            const searchTerm = $(this).val().trim();
            
            // Update container state
            if (searchTerm.length > 0) {
                autocompleteContainer.addClass('has-value');
            } else {
                autocompleteContainer.removeClass('has-value');
                hideSuggestions();
                return;
            }
            
            // Clear previous timeout
            clearTimeout(autocompleteTimeout);
            
            if (searchTerm.length < 2) {
                hideSuggestions();
                return;
            }
            
            // Show loading
            showLoading();
            
            // Debounce search
            autocompleteTimeout = setTimeout(() => {
                searchPeminjam(searchTerm);
            }, 300);
        });
        
        // Keyboard navigation
        namaInput.on('keydown', function(e) {
            const suggestions = $('.autocomplete-item:visible');
            
            switch(e.keyCode) {
                case 38: // Arrow Up
                    e.preventDefault();
                    selectedIndex = Math.max(-1, selectedIndex - 1);
                    updateSelection(suggestions);
                    break;
                    
                case 40: // Arrow Down
                    e.preventDefault();
                    selectedIndex = Math.min(suggestions.length - 1, selectedIndex + 1);
                    updateSelection(suggestions);
                    break;
                    
                case 13: // Enter
                    if (selectedIndex >= 0 && selectedIndex < currentData.length) {
                        e.preventDefault();
                        selectPeminjam(currentData[selectedIndex]);
                    }
                    break;
                    
                case 27: // Escape
                    hideSuggestions();
                    namaInput.blur();
                    break;
            }
        });
        
        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.autocomplete-container').length) {
                hideSuggestions();
            }
        });
    }
    
    function searchPeminjam(searchTerm) {
        $.ajax({
            url: '{{ route("peminjaman.peminjam.history") }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                if (response.success && response.data) {
                    currentData = response.data;
                    displaySuggestions(response.data);
                } else {
                    hideSuggestions();
                }
            },
            error: function() {
                hideSuggestions();
                console.log('Error fetching peminjam data');
            }
        });
    }
    
    function displaySuggestions(data) {
        const container = $('#autocompleteSuggestions');
        container.empty();
        selectedIndex = -1;
        
        if (data.length === 0) {
            container.html('<div class="autocomplete-item">Tidak ada data ditemukan</div>');
            container.show();
            return;
        }
        
        data.forEach((item, index) => {
            const suggestionHtml = `
                <div class="autocomplete-item" data-index="${index}">
                    <div class="autocomplete-name">
                        <i class="bi bi-person-check autocomplete-icon"></i>
                        ${item.nama}
                    </div>
                    <div class="autocomplete-details">
                        📧 ${item.email || 'Email tidak tersedia'}<br>
                        📞 ${item.telepon || 'Telepon tidak tersedia'}<br>
                        📍 ${item.alamat || 'Alamat tidak tersedia'}
                    </div>
                </div>
            `;
            container.append(suggestionHtml);
        });
        
        // Add click handlers
        $('.autocomplete-item').on('click', function() {
            const index = $(this).data('index');
            if (index !== undefined && index < data.length) {
                selectPeminjam(data[index]);
            }
        });
        
        container.show();
    }
    
    function selectPeminjam(peminjamData) {
        // Fill form fields
        $('#nama').val(peminjamData.nama).addClass('auto-filled');
        $('#email').val(peminjamData.email || '').addClass('auto-filled');
        $('#telepon').val(peminjamData.telepon || '').addClass('auto-filled');
        $('#alamat').val(peminjamData.alamat || '').addClass('auto-filled');
        
        // Show success info
        $('#autoFillInfo').removeClass('d-none');
        
        // Hide suggestions
        hideSuggestions();
        
        // Remove auto-filled class after a few seconds
        setTimeout(() => {
            $('.form-control.auto-filled').removeClass('auto-filled');
        }, 3000);
        
        // Auto-hide info after 5 seconds
        setTimeout(() => {
            $('#autoFillInfo').addClass('d-none');
        }, 5000);
    }
    
    function updateSelection(suggestions) {
        suggestions.removeClass('selected');
        if (selectedIndex >= 0) {
            suggestions.eq(selectedIndex).addClass('selected');
        }
    }
    
    function showLoading() {
        const container = $('#autocompleteSuggestions');
        container.html('<div class="autocomplete-loading"><i class="bi bi-hourglass-split"></i> Mencari data...</div>');
        container.show();
    }
    
    function hideSuggestions() {
        $('#autocompleteSuggestions').hide();
        selectedIndex = -1;
    }
    
    // Clear autocomplete function
    window.clearAutocomplete = function() {
        $('#nama').val('').removeClass('auto-filled');
        $('#email').val('').removeClass('auto-filled');
        $('#telepon').val('').removeClass('auto-filled');
        $('#alamat').val('').removeClass('auto-filled');
        $('.autocomplete-container').removeClass('has-value');
        $('#autoFillInfo').addClass('d-none');
        hideSuggestions();
        $('#nama').focus();
    };
    
    // === CALCULATION FUNCTIONALITY ===
    let appliedDiscount = {
        code: null,
        percentage: 0,
        amount: 0
    };
    
    function calculateTotal() {
        const tanggalPinjam = $('#tanggal_pinjam').val();
        const jamPinjam = $('#jam_pinjam').val();
        const tanggalKembali = $('#tanggal_kembali').val();
        const jamKembali = $('#jam_kembali').val();
        
        if (tanggalPinjam && jamPinjam && tanggalKembali && jamKembali) {
            // Gabungkan tanggal dan jam
            const startDateTime = new Date(tanggalPinjam + ' ' + jamPinjam);
            const endDateTime = new Date(tanggalKembali + ' ' + jamKembali);
            
            const timeDiffMs = endDateTime.getTime() - startDateTime.getTime();
            
            if (timeDiffMs >= 0) {
                const hours = Math.ceil(timeDiffMs / (1000 * 60 * 60)); // Perhitungan jam
                
                // Logika billing: setiap periode 24 jam = 1 hari
                // Jika melebihi waktu yang disepakati, dikenakan biaya hari berikutnya
                let billingDays;
                if (hours <= 24) {
                    billingDays = 1; // 1-24 jam = 1 hari
                } else {
                    billingDays = Math.ceil(hours / 24); // Lebih dari 24 jam = kelipatan hari
                }
                
                const subtotalAmount = subtotal * billingDays;
                
                // Calculate discount amount
                const discountAmount = appliedDiscount.percentage > 0 ? 
                    Math.round(subtotalAmount * (appliedDiscount.percentage / 100)) : 0;
                
                const total = subtotalAmount - discountAmount;
                
                // Update applied discount amount
                appliedDiscount.amount = discountAmount;
                
                // Format durasi yang simple
                let durasiText = '';
                if (hours <= 24) {
                    durasiText = hours + ' jam';
                } else {
                    const fullDays = Math.floor(hours / 24);
                    const remainingHours = hours % 24;
                    if (remainingHours === 0) {
                        durasiText = fullDays + ' hari';
                    } else {
                        durasiText = fullDays + ' hari ' + remainingHours + ' jam';
                    }
                }
                
                // Update display
                $('#durasi').text(durasiText);
                $('#durasi-display').text(durasiText);
                $('#billing-days').text(billingDays);
                
                // Update discount display
                if (appliedDiscount.percentage > 0) {
                    $('#discount-percent').text(appliedDiscount.percentage);
                    $('#discount-amount').text(discountAmount.toLocaleString('id-ID'));
                    $('#discount-info').show();
                } else {
                    $('#discount-info').hide();
                }
                
                // Simple status message
                if (hours <= 24) {
                    $('#durasi-detail').text('Sewa normal - 1 hari');
                    $('#billing-info').hide();
                } else {
                    $('#durasi-detail').html('<span class="text-danger fw-bold">Terlambat - Dikenakan ' + billingDays + ' hari billing</span>');
                    $('#billing-info').show();
                }
                
                // Always show warning about late policy
                $('#warning-denda').show();
                
                $('#total-harga').text('Rp ' + total.toLocaleString('id-ID'));
            } else {
                resetDisplay();
            }
        } else {
            resetDisplay();
        }
    }
    
    function resetDisplay() {
        $('#durasi').text('-');
        $('#durasi-display').text('-');
        $('#billing-days').text('-');
        $('#durasi-detail').text('Pilih tanggal dan jam untuk melihat durasi');
        $('#total-harga').text('Rp 0');
        $('#billing-info').hide();
        $('#discount-info').hide();
        // Keep warning visible to inform users about policy
        $('#warning-denda').show();
    }
    
    // === DISCOUNT FUNCTIONALITY ===
    function applyDiscount() {
        const discountCode = $('#kode_diskon').val().toUpperCase().trim();
        
        if (!discountCode) {
            showDiscountFeedback('Masukkan kode diskon terlebih dahulu', 'warning');
            return;
        }
        
        // Show loading
        showDiscountFeedback('<i class="spinner-border spinner-border-sm me-1"></i>Memeriksa kode diskon...', 'info');
        $('#btn-apply-discount').prop('disabled', true);
        
        // AJAX request to validate discount
        $.ajax({
            url: '{{ route("validate-discount") }}',
            method: 'POST',
            data: {
                kode_diskon: discountCode,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    appliedDiscount.code = discountCode;
                    appliedDiscount.percentage = response.discount.persentase;
                    
                    showDiscountFeedback(
                        '<i class="bi bi-check-circle-fill me-1"></i>Diskon ' + response.discount.persentase + '% berhasil diterapkan!', 
                        'success'
                    );
                    
                    $('#btn-apply-discount').text('Diterapkan').removeClass('btn-outline-primary').addClass('btn-success');
                    $('#kode_diskon').prop('readonly', true);
                    
                    // Add remove discount button
                    if (!$('#btn-remove-discount').length) {
                        $('#btn-apply-discount').after(
                            '<button class="btn btn-outline-danger btn-sm ms-1" type="button" id="btn-remove-discount" title="Hapus diskon">' +
                            '<i class="bi bi-x"></i></button>'
                        );
                    }
                    
                    calculateTotal();
                } else {
                    showDiscountFeedback('<i class="bi bi-exclamation-triangle-fill me-1"></i>' + response.message, 'danger');
                    resetDiscount();
                }
            },
            error: function() {
                showDiscountFeedback('<i class="bi bi-exclamation-triangle-fill me-1"></i>Gagal memvalidasi kode diskon', 'danger');
                resetDiscount();
            },
            complete: function() {
                $('#btn-apply-discount').prop('disabled', false);
            }
        });
    }
    
    function resetDiscount() {
        appliedDiscount = { code: null, percentage: 0, amount: 0 };
        $('#kode_diskon').prop('readonly', false);
        $('#btn-apply-discount').text('Terapkan').removeClass('btn-success').addClass('btn-outline-primary');
        $('#btn-remove-discount').remove();
        calculateTotal();
    }
    
    function showDiscountFeedback(message, type) {
        const alertClass = `alert-${type}`;
        $('#discount-feedback').html(
            `<div class="alert ${alertClass} alert-sm py-1 px-2 mb-0 small">${message}</div>`
        );
        
        if (type === 'success' || type === 'danger') {
            setTimeout(() => {
                if (type === 'success') return; // Keep success message
                $('#discount-feedback').empty();
            }, 5000);
        }
    }
    
    // Discount event handlers
    $('#btn-apply-discount').on('click', applyDiscount);
    
    $(document).on('click', '#btn-remove-discount', function() {
        resetDiscount();
        showDiscountFeedback('Diskon dihapus', 'info');
        setTimeout(() => $('#discount-feedback').empty(), 2000);
    });
    
    // Allow Enter key to apply discount
    $('#kode_diskon').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            applyDiscount();
        }
    });
    
    // Auto-uppercase discount code
    $('#kode_diskon').on('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    $('#tanggal_pinjam, #jam_pinjam, #tanggal_kembali, #jam_kembali').on('change', function() {
        calculateTotal();
        checkAvailability();
    });
    
    // Set minimum date and time for return fields
    $('#tanggal_pinjam, #jam_pinjam').on('change', function() {
        const selectedDate = $('#tanggal_pinjam').val();
        const selectedTime = $('#jam_pinjam').val();
        
        if (selectedDate) {
            $('#tanggal_kembali').attr('min', selectedDate);
            
            // If return date is before start date, reset it
            if ($('#tanggal_kembali').val() && $('#tanggal_kembali').val() < selectedDate) {
                $('#tanggal_kembali').val('');
                $('#jam_kembali').val('');
            }
            
            // If same date, ensure return time is after start time
            if ($('#tanggal_kembali').val() === selectedDate && selectedTime) {
                const currentReturnTime = $('#jam_kembali').val();
                if (currentReturnTime && currentReturnTime <= selectedTime) {
                    // Add 1 hour to start time as minimum return time
                    const startTime = new Date('2000-01-01 ' + selectedTime);
                    startTime.setHours(startTime.getHours() + 1);
                    const minReturnTime = startTime.toTimeString().substr(0, 5);
                    $('#jam_kembali').val(minReturnTime);
                }
            }
        }
        
        calculateTotal();
    });
    
    // Function to check availability for all cart items
    function checkAvailability() {
        const tanggalPinjam = $('#tanggal_pinjam').val();
        const jamPinjam = $('#jam_pinjam').val();
        const tanggalKembali = $('#tanggal_kembali').val();
        const jamKembali = $('#jam_kembali').val();
        
        // Clear previous availability messages
        $('.availability-message').remove();
        $('#checkout-form button[type="submit"]').prop('disabled', false);
        
        if (!tanggalPinjam || !jamPinjam || !tanggalKembali || !jamKembali) {
            return; // Don't check if dates are incomplete
        }
        
        const startDateTime = tanggalPinjam + ' ' + jamPinjam + ':00';
        const endDateTime = tanggalKembali + ' ' + jamKembali + ':00';
        
        // Get cart items and check each one
        const cartItems = [
            @if(isset($cartItems) && count($cartItems) > 0)
                @foreach($cartItems as $item)
                    {
                        id: {{ $item['id'] }},
                        quantity: {{ $item['quantity'] }},
                        nama: '{{ addslashes($item['nama']) }}'
                    },
                @endforeach
            @endif
        ];
        
        cartItems.forEach(function(item) {
            checkItemAvailability(item.id, item.quantity, item.nama, startDateTime, endDateTime);
        });
    }
    
    function checkItemAvailability(barangId, quantity, namaBarang, startDateTime, endDateTime) {
        $.ajax({
            url: '{{ route("peminjaman.check.availability") }}',
            method: 'POST',
            data: {
                barang_id: barangId,
                tanggal_pinjam: startDateTime,
                tanggal_kembali: endDateTime,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    const availability = response.availability;
                    
                    if (!availability.available) {
                        // Show warning message
                        const warningHtml = `
                            <div class="alert alert-warning alert-dismissible fade show availability-message mt-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>${namaBarang}:</strong> ${availability.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        $('.rental-period-section').after(warningHtml);
                        
                        // Disable submit button
                        $('#checkout-form button[type="submit"]').prop('disabled', true);
                    }
                }
            },
            error: function() {
                console.error('Failed to check availability for ' + namaBarang);
            }
        });
    }
    
    // === AVAILABILITY CHECKING FUNCTIONALITY ===
    function checkCartAvailability() {
        const startDate = $('#tanggal_pinjam').val();
        const endDate = $('#tanggal_kembali').val();
        const startTime = $('#jam_pinjam').val();
        const endTime = $('#jam_kembali').val();
        
        if (!startDate || !endDate) {
            return;
        }
        
        // Clear previous availability messages
        $('.availability-message').remove();
        $('#checkout-form button[type="submit"]').prop('disabled', false);
        
        const startDateTime = startDate + ' ' + (startTime || '08:00');
        const endDateTime = endDate + ' ' + (endTime || '17:00');
        
        // Cart items data from PHP - with safety check
        const cartItems = @json($cartItems ?? []);
        
        // Check if cart is empty
        if (!cartItems || Object.keys(cartItems).length === 0) {
            console.log('[Availability] Cart is empty, skipping availability check');
            return;
        }
        
        console.log('[Availability] Checking for period:', startDateTime, 'to', endDateTime);
        console.log('[Availability] Cart items:', cartItems);
        
        // Check each item in cart
        Object.entries(cartItems).forEach(([itemId, item]) => {
            checkItemAvailability(item.id, item.quantity, item.nama, startDateTime, endDateTime);
        });
    }
    
    function checkItemAvailability(barangId, quantity, namaBarang, startDateTime, endDateTime) {
        console.log(`[Availability] Checking ${namaBarang} (${quantity} units) for ${startDateTime} - ${endDateTime}`);
        
        $.ajax({
            url: '{{ route("peminjaman.check.availability") }}',
            method: 'POST',
            data: {
                barang_id: parseInt(barangId),
                tanggal_pinjam: startDateTime,
                tanggal_kembali: endDateTime,
                quantity: parseInt(quantity),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(`[Availability] Response for ${namaBarang}:`, response);
                
                if (response.success) {
                    const availability = response.availability;
                    
                    if (!availability.available) {
                        // Show warning message for unavailable item
                        const warningHtml = `
                            <div class="alert alert-warning alert-dismissible fade show availability-message mt-2" data-item-id="${barangId}">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>${namaBarang}:</strong> ${availability.message}
                                <small class="d-block mt-1 text-muted">
                                    Tersedia: ${availability.available_stock} unit, Diperlukan: ${quantity} unit
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        $('#checkout-availability-warnings').append(warningHtml);
                        
                        // Disable submit button if any item is unavailable
                        $('#checkout-form button[type="submit"]').prop('disabled', true);
                        
                        console.log(`[Availability] ${namaBarang} is NOT available - submit disabled`);
                    } else {
                        console.log(`[Availability] ${namaBarang} is available - ${availability.available_stock} units`);
                    }
                } else {
                    console.error(`[Availability] Error checking ${namaBarang}:`, response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(`[Availability] AJAX error for ${barangId}: ${status} ${error}`);
                console.error('[Availability] XHR Response:', xhr.responseText);
                
                // Show error message
                const errorHtml = `
                    <div class="alert alert-danger alert-dismissible fade show availability-message mt-2">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error:</strong> Tidak dapat mengecek ketersediaan ${namaBarang}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#checkout-availability-warnings').append(errorHtml);
                
                // Disable submit button on error
                $('#checkout-form button[type="submit"]').prop('disabled', true);
            }
        });
    }
    
    // Initialize everything
    initAutocomplete();
    calculateTotal();
    
    // Add event listeners for date/time changes
    $('#tanggal_pinjam, #tanggal_kembali, #jam_pinjam, #jam_kembali').on('change', function() {
        calculateTotal();
        
        // Debounce availability checking
        clearTimeout(window.availabilityTimeout);
        window.availabilityTimeout = setTimeout(checkCartAvailability, 500);
    });
    
    // Initial availability check
    setTimeout(checkCartAvailability, 1000);
    
    // Load unavailable dates for cart items
    let unavailableDates = [];
    let dateMessages = {};

    function loadUnavailableDates() {
        console.log('[DatePicker] Loading unavailable dates for cart items...');
        
        $.ajax({
            url: '{{ route("peminjaman.cart.unavailable.dates") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    unavailableDates = response.unavailable_dates || [];
                    dateMessages = response.messages || {};
                    
                    console.log('[DatePicker] Loaded unavailable dates:', unavailableDates.length);
                    setupDatePickers();
                } else {
                    console.error('[DatePicker] Error loading unavailable dates:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('[DatePicker] AJAX error loading unavailable dates:', status, error);
            }
        });
    }

    function setupDatePickers() {
        // Apply date restrictions to date inputs
        const dateInputs = ['#tanggal_pinjam', '#tanggal_kembali'];
        
        dateInputs.forEach(function(selector) {
            const input = $(selector)[0];
            if (input) {
                // Add event listener for date input validation
                input.addEventListener('input', function(e) {
                    validateSelectedDate(e.target);
                });
                
                // Initial validation
                if (input.value) {
                    validateSelectedDate(input);
                }
            }
        });
    }

    function validateSelectedDate(input) {
        const selectedDate = input.value;
        const inputId = input.id;
        
        if (selectedDate && unavailableDates.includes(selectedDate)) {
            // Show warning message
            showDateWarning(input, selectedDate);
            
            // Clear the input
            input.value = '';
            
            // Focus back to the input for user to select another date
            setTimeout(() => input.focus(), 100);
        } else {
            // Remove any existing warnings for this input
            removeDateWarning(input);
        }
    }

    function showDateWarning(input, selectedDate) {
        const inputContainer = $(input).closest('.mb-3');
        const existingWarning = inputContainer.find('.date-warning');
        
        if (existingWarning.length === 0) {
            const messages = dateMessages[selectedDate] || [];
            let messageText = '';
            
            if (messages.length > 0) {
                if (messages.length === 1) {
                    messageText = `${messages[0].nama} tidak tersedia di tanggal yang dipilih.`;
                } else {
                    messageText = 'Produk berikut tidak tersedia di tanggal yang dipilih: ';
                    messages.forEach((msg, index) => {
                        messageText += `${msg.nama}`;
                        if (index < messages.length - 1) {
                            messageText += ', ';
                        } else if (index === messages.length - 1 && messages.length > 1) {
                            messageText += '.';
                        }
                    });
                }
            } else {
                messageText = 'Tanggal yang dipilih tidak tersedia.';
            }
            
            const warningHtml = `
                <div class="alert alert-warning alert-sm mt-2 date-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <small>${messageText}</small>
                </div>
            `;
            
            inputContainer.append(warningHtml);
        }
    }

    function removeDateWarning(input) {
        const inputContainer = $(input).closest('.mb-3');
        inputContainer.find('.date-warning').remove();
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long', 
            day: 'numeric'
        });
    }

    // Load unavailable dates on page load
    loadUnavailableDates();

    // Reload unavailable dates when cart changes (if needed)
    window.reloadUnavailableDates = loadUnavailableDates;
    
    // Show warning policy by default
    $('#warning-denda').show();
    
    // Auto-show kebijakan pengembalian modal when page loads
    setTimeout(function() {
        $('#lateFeeModal').modal('show');
        console.log('[Checkout] Auto-showing kebijakan pengembalian modal');
    }, 1500); // Delay 1.5 seconds after page load for better UX
});
</script>
@endpush
