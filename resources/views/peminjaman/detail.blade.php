@extends('layouts.marketplace')

@section('content')
<style>
/* Custom styles for responsive product detail */
.product-detail-container {
    min-height: 100vh;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.card.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
}

.text-primary {
    color: #0d6efd !important;
}

/* Responsive image container - only image is sticky */
.product-image-container {
    position: sticky;
    top: 30px;
    height: fit-content;
    margin-bottom: 2rem;
    padding-right: 0;
}

.product-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    overflow: hidden;
    border-radius: 12px;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease, opacity 0.3s ease;
    opacity: 0;
}

.product-image-wrapper img[onload] {
    opacity: 1;
}

.product-image-wrapper:hover img {
    transform: scale(1.02);
}

/* Product header - not sticky on mobile */
.product-header {
    margin-bottom: 1.5rem;
    padding-top: 0.5rem;
}

/* Product details column with proper spacing */
.product-details-column {
    padding-left: 0;
}

/* Description with expandable functionality */
.product-description {
    line-height: 1.6;
    color: #6c757d;
}

.product-description.collapsed {
    max-height: 4.8em; /* 3 lines */
    overflow: hidden;
    position: relative;
}

.product-description.collapsed::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1.2em;
    background: linear-gradient(transparent, #fff);
}

.description-toggle {
    color: #0d6efd;
    cursor: pointer;
    font-size: 0.9rem;
    margin-top: 0.5rem;
    border: none;
    background: none;
    padding: 0;
}

.description-toggle:hover {
    text-decoration: underline;
}

.product-description::-webkit-scrollbar {
    width: 6px;
}

.product-description::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.product-description::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.product-description::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Fixed height sections for alignment */
.info-card {
    min-height: 200px;
}

.action-section {
    margin-top: auto;
}

/* Responsive layout adjustments */
@media (max-width: 991.98px) {
    .product-image-container {
        position: relative;
        top: 0;
        margin-bottom: 2rem;
        padding-right: 0;
    }
    
    .product-details-column {
        padding-left: 0;
        margin-top: 1rem;
    }
    
    .product-header {
        margin-bottom: 1rem;
        padding-top: 0;
    }
}

@media (max-width: 767.98px) and (min-width: 576px) {
    .product-image-container {
        padding-right: 1rem;
        margin-bottom: 2rem;
    }
    
    .product-details-column {
        padding-left: 1rem;
        margin-top: 0.5rem;
    }
}

@media (max-width: 575.98px) {
    .product-image-container {
        margin-bottom: 1.5rem;
        padding-right: 0;
    }
    
    .product-image-wrapper {
        border-radius: 8px;
        max-height: 250px;
    }
    
    .product-details-column {
        padding-left: 0;
        margin-top: 1rem;
    }
    
    .product-header h1 {
        font-size: 1.5rem;
    }
}

@media (min-width: 992px) {
    .product-details-column {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding-left: 2rem;
    }
    
    .product-image-container {
        top: 40px;
        padding-right: 2.5rem;
    }
    
    .product-header {
        padding-top: 1rem;
        margin-bottom: 2rem;
    }
}
</style>

<div class="container py-5 product-detail-container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $barang->nama }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Product Image Section -->
        <div class="col-lg-5">
            <div class="product-image-container">
                <div class="product-image-wrapper">
                    @if($barang->gambar && \Storage::disk('public')->exists($barang->gambar))
                        <img src="{{ asset('storage/' . $barang->gambar) }}" 
                             alt="{{ $barang->nama }}" 
                             loading="lazy"
                             onload="this.style.opacity='1'"
                             onerror="this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100 bg-light\'><div class=\'text-center\'><i class=\'fas fa-image text-muted mb-3\' style=\'font-size: 4rem;\'></i><div class=\'text-muted\'>Gambar tidak tersedia</div></div></div>'">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                            <div class="text-center">
                                <i class="fas fa-image text-muted mb-3" style="font-size: 4rem;"></i>
                                <div class="text-muted">Gambar tidak tersedia</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Details Section -->
        <div class="col-lg-7">
            <div class="product-details-column">
                <!-- Product Header - Not Sticky -->
                <div class="product-header">
                    <h1 class="h2 mb-3">{{ $barang->nama }}</h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary me-2">{{ $barang->jenisBarang->nama }}</span>
                        <div id="availabilityBadge" class="d-inline">
                            @if(isset($barang->available_stock))
                                @if($barang->available_stock == 0)
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @elseif($barang->available_stock < 3)
                                    <span class="badge bg-warning text-dark">Stok Terbatas</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                    Mengecek ketersediaan...
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="h3 text-primary mb-1">Rp {{ number_format($barang->harga_hari, 0, ',', '.') }}</div>
                        <small class="text-muted">per hari</small>
                    </div>
                </div>

                <!-- Description with expandable functionality -->
                <div class="mb-4">
                    <h5 class="mb-3">Deskripsi</h5>
                    @if($barang->deskripsi)
                        @php
                            $description = $barang->deskripsi;
                            $isLong = strlen($description) > 200;
                        @endphp
                        
                        <div id="productDescription" class="product-description {{ $isLong ? 'collapsed' : '' }}">
                            {{ $description }}
                        </div>
                        
                        @if($isLong)
                            <button type="button" class="description-toggle" onclick="toggleDescription()">
                                <span id="toggleText">Selengkapnya</span>
                                <i id="toggleIcon" class="fas fa-chevron-down ms-1"></i>
                            </button>
                        @endif
                    @else
                        <div class="product-description text-muted">
                            Deskripsi belum tersedia.
                        </div>
                    @endif
                </div>
                
                <!-- Enhanced Stock Information -->
                <div class="card border-0 bg-light mb-4 info-card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informasi Ketersediaan
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">Total Stok:</small>
                                    <span class="badge bg-secondary">{{ $barang->stok }} unit</span>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">Dipinjam:</small>
                                    <span class="badge bg-warning text-dark">{{ $barang->stok - $barang->available_stock }} unit</span>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <hr class="my-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong class="text-success">Tersedia Hari Ini:</strong>
                                    @if($barang->available_stock == 0)
                                        <span class="badge bg-danger">Tidak Tersedia</span>
                                    @elseif($barang->available_stock < 3)
                                        <span class="badge bg-warning text-dark">{{ $barang->available_stock }} unit</span>
                                    @else
                                        <span class="badge bg-success">{{ $barang->available_stock }} unit</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <small class="text-muted">Per {{ now()->format('d M Y H:i') }} WIB</small>
                                    @if($barang->available_stock == 0)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Tidak Tersedia
                                        </span>
                                    @elseif($barang->available_stock < 3)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Terbatas
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($barang->availability_message))
                            <div class="alert alert-info alert-sm mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ $barang->availability_message }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Date Picker Section -->
                <div class="card border-0 shadow-sm mb-4" id="datePicker">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Pilih Tanggal Rental
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="rentalDate" class="form-label fw-semibold">Tanggal Mulai Rental</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-primary">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control border-primary" 
                                           id="rentalDate" 
                                           name="rental_date"
                                           value="{{ now()->format('Y-m-d') }}"
                                           min="{{ now()->format('Y-m-d') }}">
                                </div>
                                <small class="text-muted">Minimal hari ini ({{ now()->format('d M Y') }})</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status Ketersediaan</label>
                                <div id="availabilityDisplay" class="mt-2">
                                    <!-- Initial status will be loaded here -->
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <small class="text-muted">Mengecek ketersediaan...</small>
                                    </div>
                                </div>
                                <small class="text-muted">Akan update otomatis saat tanggal berubah</small>
                            </div>
                        </div>
                        
                        <!-- Additional Info Row -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-light border-0 mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        <small class="text-muted mb-0">
                                            <strong>Tips:</strong> Pilih tanggal untuk melihat ketersediaan real-time dan pastikan stok tersedia sebelum menambah ke keranjang.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Section -->
                <div class="action-section">
                    <!-- Quick Info Row -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Kategori:</small><br>
                            <span class="fw-semibold">{{ $barang->jenisBarang->nama }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Harga/hari:</small><br>
                            <span class="text-primary fw-bold">Rp {{ number_format($barang->harga_hari, 0, ',', '.') }}</span>
                        </div>
                    </div>
                
                <!-- Simplified Add to Cart Section -->
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-2">Harga Rental</label>
                                <div class="alert alert-info py-3 mb-0 d-flex align-items-center justify-content-center" style="min-height: 70px;">
                                    <div class="text-center">
                                        <i class="fas fa-calculator mb-1 d-block text-primary"></i>
                                        <div class="text-primary fw-bold mb-0">Rp {{ number_format($barang->harga_hari, 0, ',', '.') }}</div>
                                        <small class="text-muted">/hari</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-2">Action</label>
                                <div id="actionButtonContainer" style="min-height: 70px;" class="d-flex flex-column justify-content-center">
                                    <!-- Simple cart button - always functional, only label changes -->
                                    <!-- Action button - initial state, will be updated by JavaScript -->
                                    @if(($barang->available_stock ?? 0) > 0)
                                        <button type="button" id="addToCartBtn" class="btn btn-primary w-100 mb-2" onclick="addToCart({{ $barang->id }}, 1)">
                                            <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                        </button>
                                    @else
                                        <button type="button" id="addToCartBtn" class="btn btn-warning w-100 mb-2" onclick="addToCart({{ $barang->id }}, 1)">
                                            <i class="fas fa-calendar-plus me-2"></i>Booking
                                        </button>
                                    @endif
                                    <!-- Dynamic stock info - updated by JavaScript -->
                                    <small id="stockInfo" class="text-muted d-block text-center mt-2">
                                        @if(isset($barang->available_stock))
                                            @if($barang->available_stock > 0)
                                                <i class="fas fa-check-circle me-1"></i>Stok tersedia: {{ $barang->available_stock }} unit
                                            @else
                                                <i class="fas fa-info-circle me-1"></i>Mengecek ketersediaan...
                                            @endif
                                        @else
                                            <i class="fas fa-spinner fa-spin me-1"></i>Mengecek ketersediaan...
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('peminjaman.cart') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> Lihat Keranjang
                    </a>
                </div>
                
                <hr class="my-4">
                
                <div class="small text-muted">
                    <h6>Informasi Penting:</h6>
                    <ul class="mb-0">
                        <li>Barang harus dikembalikan sesuai tanggal yang disepakati</li>
                        <li>Kerusakan atau kehilangan menjadi tanggung jawab peminjam</li>
                        <li>Pembayaran dilakukan di muka sebelum barang diserahkan</li>
                        <li>Hubungi customer service untuk informasi lebih lanjut</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <hr class="my-5">
    
    <!-- Related Products -->
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">
                <i class="fas fa-layer-group me-2 text-primary"></i>Produk Serupa
            </h3>
            
            @php
                $relatedProducts = App\Models\Barang::where('jenis_barang_id', $barang->jenis_barang_id)
                    ->where('id', '!=', $barang->id)
                    ->where('stok', '>', 0)
                    ->limit(4)
                    ->get();
            @endphp
            
            @if($relatedProducts->count() > 0)
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="cursor: pointer; transition: all 0.3s ease;" onclick="goToProductDetail({{ $related->id }})">
                        <div class="position-relative overflow-hidden">
                            @if($related->gambar && \Storage::disk('public')->exists($related->gambar))
                                <img src="{{ asset('storage/' . $related->gambar) }}" 
                                     class="card-img-top" 
                                     alt="{{ $related->nama }}" 
                                     style="height: 180px; object-fit: cover; transition: transform 0.3s ease;"
                                     loading="lazy"
                                     onload="this.style.opacity='1'"
                                     onerror="this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center bg-light\' style=\'height: 180px;\'><i class=\'fas fa-image text-muted\' style=\'font-size: 2rem;\'></i></div>'">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px;">
                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            
                            @if($related->stok < 5)
                                <span class="badge bg-warning position-absolute top-0 start-0 m-2 small">
                                    Stok Terbatas
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title mb-2 fw-semibold" style="line-height: 1.4; min-height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ $related->nama }}
                            </h6>
                            <p class="text-muted small mb-2">{{ $related->jenisBarang->nama }}</p>
                            
                            <div class="mb-3 mt-auto">
                                <div class="h6 text-primary mb-0 fw-bold">Rp {{ number_format($related->harga_hari, 0, ',', '.') }}</div>
                                <small class="text-muted">per hari</small>
                            </div>
                            
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary btn-sm fw-semibold" onclick="event.stopPropagation(); addToCartQuick({{ $related->id }}, this)">
                                    <i class="fas fa-cart-plus me-1"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-box-open mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="mb-0">Tidak ada produk serupa yang tersedia saat ini.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
/* Professional product card styles */
.card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.card:hover img {
    transform: scale(1.03);
}

.card img {
    transition: transform 0.3s ease;
    border-radius: 12px 12px 0 0;
}

/* Professional button styles */
.btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary:hover {
    background-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Loading state */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -8px 0 0 -8px;
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Badge styles */
.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
    border-radius: 6px;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
    
    .card-title {
        font-size: 0.95rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>

<script>
// Define stock variables with proper fallback
let AVAILABLE_STOCK = {{ $barang->available_stock ?? 0 }};
const TOTAL_STOCK = {{ $barang->stok ?? 0 }};
const PRICE_PER_DAY = {{ $barang->harga_hari }};

// Make AVAILABLE_STOCK globally accessible so it can be updated
window.AVAILABLE_STOCK = AVAILABLE_STOCK;

console.log('Stock Info:', {
    available: AVAILABLE_STOCK,
    total: TOTAL_STOCK,
    price: PRICE_PER_DAY
});

// Simple stock checking - for display purposes only

// Toggle description functionality
function toggleDescription() {
    const description = document.getElementById('productDescription');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (description.classList.contains('collapsed')) {
        description.classList.remove('collapsed');
        toggleText.textContent = 'Lebih sedikit';
        toggleIcon.classList.remove('fa-chevron-down');
        toggleIcon.classList.add('fa-chevron-up');
    } else {
        description.classList.add('collapsed');
        toggleText.textContent = 'Selengkapnya';
        toggleIcon.classList.remove('fa-chevron-up');
        toggleIcon.classList.add('fa-chevron-down');
    }
}

// Fast navigation to product detail page - optimized for speed
function goToProductDetail(productId) {
    if (productId) {
        // Check cache first for instant navigation
        if (window.relatedProductUrls && window.relatedProductUrls[productId]) {
            window.location.href = window.relatedProductUrls[productId];
            return;
        }
        
        // Fast single URL generation
        fetch(`/peminjaman/encrypt-url/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.url;
                } else {
                    console.error('Failed to get encrypted URL');
                    // Use new short format as fallback
                    window.location.href = `/barang?id=temp_${productId}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Use new short format as fallback
                window.location.href = `/barang?id=temp_${productId}`;
            });
    }
}

// Quick add to cart function for related products
function addToCartQuick(barangId, buttonElement) {
    const originalContent = buttonElement.innerHTML;
    
    // Show loading state
    buttonElement.classList.add('btn-loading');
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menambah...';
    buttonElement.disabled = true;
    
    fetch('/peminjaman/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            barang_id: barangId,
            quantity: 1
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Success state
            buttonElement.classList.remove('btn-loading', 'btn-primary');
            buttonElement.classList.add('btn-success');
            buttonElement.innerHTML = '<i class="fas fa-check me-1"></i>Berhasil!';
            
            // Update cart count
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
            
            // Show notification
            if (typeof showNotification === 'function') {
                showNotification(data.message || 'Berhasil ditambahkan ke keranjang!', 'success');
            }
            
            // Reset button after 2 seconds
            setTimeout(() => {
                buttonElement.classList.remove('btn-success');
                buttonElement.classList.add('btn-primary');
                buttonElement.innerHTML = originalContent;
                buttonElement.disabled = false;
            }, 2000);
            
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        
        // Error state
        buttonElement.classList.remove('btn-loading', 'btn-primary');
        buttonElement.classList.add('btn-danger');
        buttonElement.innerHTML = '<i class="fas fa-times me-1"></i>Gagal';
        
        // Show error notification
        if (typeof showNotification === 'function') {
            showNotification(error.message || 'Terjadi kesalahan', 'error');
        }
        
        // Reset button after 2 seconds
        setTimeout(() => {
            buttonElement.classList.remove('btn-danger');
            buttonElement.classList.add('btn-primary');
            buttonElement.innerHTML = originalContent;
            buttonElement.disabled = false;
        }, 2000);
    });
}

// Cart functionality uses global addToCart function from marketplace layout
// No custom functions needed - keep it simple!

// Initialize on page load  
document.addEventListener('DOMContentLoaded', function() {
    // Initialize availability checker for stock display
    initAvailabilityChecker();
    
    // Pre-cache related product URLs for instant navigation
    preLoadRelatedProductUrls();
    
    console.log('Detail page: Simple cart system initialized');
});

// Pre-load related product URLs for faster navigation
function preLoadRelatedProductUrls() {
    const relatedProducts = document.querySelectorAll('.card[onclick*="goToProductDetail"]');
    
    if (relatedProducts.length > 0) {
        const productIds = [];
        
        relatedProducts.forEach(card => {
            const onclickAttr = card.getAttribute('onclick');
            const match = onclickAttr.match(/goToProductDetail\((\d+)\)/);
            if (match) {
                productIds.push(match[1]);
            }
        });
        
        if (productIds.length > 0) {
            console.log('[Related URLs] Pre-loading URLs for', productIds.length, 'related products...');
            
            fetch('/peminjaman/batch-encrypt-urls', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ids: productIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.relatedProductUrls = data.urls;
                    console.log('[Related URLs] ✅ Cached', Object.keys(data.urls).length, 'related product URLs');
                } else {
                    console.warn('[Related URLs] Failed to cache URLs:', data.message);
                }
            })
            .catch(error => {
                console.warn('[Related URLs] Error caching URLs:', error);
            });
        }
    }
}

/**
 * Real-time Availability Checking System
 * Checks product availability when date is changed
 */

// Configuration
const AVAILABILITY_CONFIG = {
    endpoints: {
        checkAvailability: '/barang' // Use new short URL endpoint
    },
    elements: {
        dateInput: 'rentalDate',
        availabilityDisplay: 'availabilityDisplay',
        quantityInput: 'quantity',
        addToCartBtn: 'addToCartBtn'
    }
};

/**
 * Main function to check availability for selected date
 */
function checkAvailabilityForDate() {
    const dateInput = document.getElementById(AVAILABILITY_CONFIG.elements.dateInput);
    const availabilityDisplay = document.getElementById(AVAILABILITY_CONFIG.elements.availabilityDisplay);
    
    // Null checking - Essential for preventing errors
    if (!dateInput) {
        console.warn('[Availability] Date input element not found');
        return;
    }
    
    if (!availabilityDisplay) {
        console.warn('[Availability] Availability display element not found');
        return;
    }
    
    const selectedDate = dateInput.value;
    
    if (!selectedDate) {
        console.warn('[Availability] No date selected');
        return;
    }
    
    console.log('[Availability] Checking availability for date:', selectedDate);
    
    // Show loading state
    showLoadingState(availabilityDisplay);
    
    // Make AJAX request
    makeAvailabilityRequest(selectedDate)
        .then(data => handleAvailabilityResponse(data, selectedDate))
        .catch(error => handleAvailabilityError(error, availabilityDisplay));
}

/**
 * Show loading state in UI
 */
function showLoadingState(displayElement) {
    displayElement.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <small class="text-muted">Mengecek ketersediaan...</small>
        </div>
    `;
}

/**
 * Make AJAX request to check availability
 */
async function makeAvailabilityRequest(date) {
    // Get encrypted product ID from current URL
    let encryptedId = null;
    
    console.log('[Availability] Current URL:', window.location.href);
    
    // Check if we're on the new short URL format (/barang?id=encrypted) - PREFERRED
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('id')) {
        encryptedId = urlParams.get('id');
        console.log('[Availability] ✅ Using NEW short URL format, encrypted ID:', encryptedId);
    } else {
        // Fallback for old URL format (/peminjaman/barang/encrypted or /peminjaman/show/encrypted)
        const currentPath = window.location.pathname;
        encryptedId = currentPath.split('/').pop();
        console.log('[Availability] ⚠️ Using OLD URL format, encrypted ID:', encryptedId);
    }
    
    if (!encryptedId) {
        throw new Error('Product ID not found in URL');
    }
    
    // Use the new short route for availability checking
    const url = `/barang?id=${encryptedId}&check_date=${date}`;
    console.log('[Availability] Making request to:', url);
    
    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    });
    
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    
    return await response.json();
}

/**
 * Handle successful availability response
 */
function handleAvailabilityResponse(data, selectedDate) {
    if (!data.success) {
        throw new Error(data.message || 'Server returned unsuccessful response');
    }
    
    const availabilityDisplay = document.getElementById(AVAILABILITY_CONFIG.elements.availabilityDisplay);
    const stock = data.available_stock || 0;
    
    // Update availability display
    updateAvailabilityDisplay(availabilityDisplay, stock, selectedDate);
    
    // Update form elements
    updateFormElements(stock);
    
    // Update global variables
    updateGlobalVariables(stock);
    
    console.log(`[Availability] Updated: ${stock} units available for ${selectedDate}`);
}

/**
 * Update availability display UI
 */
function updateAvailabilityDisplay(displayElement, stock, date) {
    let statusBadge = '';
    let statusIcon = '';
    let statusText = '';
    let badgeClass = '';
    let additionalText = '';
    
    if (stock === 0) {
        statusIcon = 'fas fa-times-circle';
        statusText = 'Tidak tersedia';
        additionalText = 'pilih tanggal lain';
        badgeClass = 'bg-danger';
    } else if (stock < 3) {
        statusIcon = 'fas fa-exclamation-triangle';
        statusText = `${stock} unit tersedia`;
        additionalText = 'stok terbatas';
        badgeClass = 'bg-warning text-dark';
    } else {
        statusIcon = 'fas fa-check-circle';
        statusText = `${stock} unit tersedia`;
        additionalText = 'stok cukup';
        badgeClass = 'bg-success';
    }
    
    statusBadge = `
        <div class="availability-status mb-2">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="badge ${badgeClass} fs-6">
                    <i class="${statusIcon} me-1"></i>${statusText}
                </span>
                <small class="text-muted">${formatDate(date)}</small>
            </div>
            ${additionalText ? `<small class="text-muted d-block"><i class="fas fa-info-circle me-1"></i>${additionalText}</small>` : ''}
        </div>
    `;
    
    displayElement.innerHTML = statusBadge;
}

/**
 * Update form elements based on availability
 */
function updateFormElements(stock) {
    console.log(`[Button Update] 🔍 Starting button update, stock: ${stock}`);
    
    // Try multiple selectors to find the button
    let addToCartBtn = document.getElementById('addToCartBtn');
    console.log(`[Button Update] Selector 1 (ID): addToCartBtn found:`, !!addToCartBtn);
    
    if (!addToCartBtn) {
        addToCartBtn = document.querySelector('[onclick*="addToCart"]');
        console.log(`[Button Update] Selector 2 (onclick): addToCart found:`, !!addToCartBtn);
    }
    if (!addToCartBtn) {
        addToCartBtn = document.querySelector('.btn-primary, .btn-warning');
        console.log(`[Button Update] Selector 3 (class): btn-primary/warning found:`, !!addToCartBtn);
    }
    if (!addToCartBtn) {
        addToCartBtn = document.querySelector('button[type="button"]');
        console.log(`[Button Update] Selector 4 (button type): button found:`, !!addToCartBtn);
    }
    
    console.log(`[Button Update] Final button element:`, addToCartBtn);
    
    // Update add to cart button - always enabled, only label changes
    if (addToCartBtn) {
        const oldText = addToCartBtn.innerHTML;
        const oldClass = addToCartBtn.className;
        
        // Always keep button enabled and functional
        addToCartBtn.disabled = false;
        
        if (stock > 0) {
            // Stock available - show "Tambah ke Keranjang"
            addToCartBtn.className = 'btn btn-primary w-100';
            addToCartBtn.innerHTML = '<i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang';
            console.log(`[Button Update] ✅ CHANGED from "${oldText}" to "Tambah ke Keranjang"`);
            console.log(`[Button Update] ✅ CLASS changed from "${oldClass}" to "${addToCartBtn.className}"`);
        } else {
            // Stock unavailable - show "Booking" but still functional
            addToCartBtn.className = 'btn btn-warning w-100';
            addToCartBtn.innerHTML = '<i class="fas fa-calendar-plus me-2"></i>Booking';
            console.log(`[Button Update] ⚠️ CHANGED from "${oldText}" to "Booking"`);
            console.log(`[Button Update] ⚠️ CLASS changed from "${oldClass}" to "${addToCartBtn.className}"`);
        }
    } else {
        console.error(`[Button Update] ❌ NO BUTTON FOUND WITH ANY SELECTOR!`);
        // Debug: show all buttons on page
        const allButtons = document.querySelectorAll('button');
        console.log(`[Button Update] 🔍 All buttons on page:`, allButtons);
        allButtons.forEach((btn, index) => {
            console.log(`[Button Update] Button ${index}:`, btn.outerHTML);
        });
    }
    
    // Update stock info text
    updateStockInfo(stock);
}

function updateStockInfo(stock) {
    // Find the dedicated stock info element by ID
    const stockInfo = document.getElementById('stockInfo');
    
    console.log(`[Stock Info Update] 🔍 Looking for stockInfo element:`, stockInfo);
    
    if (stockInfo) {
        if (stock > 0) {
            stockInfo.className = 'text-success d-block text-center mt-2';
            stockInfo.innerHTML = '<i class="fas fa-check-circle me-1"></i>Stok tersedia: ' + stock + ' unit';
            console.log(`[Stock Info Update] ✅ Updated to AVAILABLE: ${stock} units`);
        } else {
            stockInfo.className = 'text-warning d-block text-center mt-2';
            stockInfo.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Stok tidak tersedia untuk tanggal ini';
            console.log(`[Stock Info Update] ⚠️ Updated to UNAVAILABLE for selected date`);
        }
    } else {
        console.error(`[Stock Info Update] ❌ stockInfo element not found!`);
    }
    
    console.log(`[Stock Info Update] Final status: Stock: ${stock}, Message: ${stock > 0 ? 'Tersedia' : 'Tidak tersedia untuk tanggal ini'}`);
}

/**
 * Update global variables
 */
function updateGlobalVariables(stock) {
    // Update global stock variables
    if (typeof window.AVAILABLE_STOCK !== 'undefined') {
        window.AVAILABLE_STOCK = stock;
        console.log('Updated AVAILABLE_STOCK to:', stock);
    }
    
    if (typeof window.MAX_STOCK !== 'undefined') {
        window.MAX_STOCK = stock;
    }
}

/**
 * Handle availability check errors
 */
function handleAvailabilityError(error, displayElement) {
    console.error('[Availability] Error:', error);
    
    displayElement.innerHTML = `
        <div class="d-flex align-items-center">
            <span class="badge bg-danger fs-6">
                <i class="fas fa-exclamation-triangle me-1"></i>Error mengecek ketersediaan
            </span>
        </div>
    `;
    
    // Show user-friendly notification if available
    if (typeof window.showNotification === 'function') {
        window.showNotification('Gagal mengecek ketersediaan. Silakan coba lagi.', 'error');
    }
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('id-ID', options);
}

/**
 * Initialize availability checking system
 */
function initAvailabilityChecker() {
    console.log('[Availability] 🚀 Initializing availability checker...');
    console.log('[Availability] Looking for date input:', AVAILABILITY_CONFIG.elements.dateInput);
    
    const dateInput = document.getElementById(AVAILABILITY_CONFIG.elements.dateInput);
    
    if (dateInput) {
        console.log('[Availability] ✅ Date input found:', dateInput);
        console.log('[Availability] Current date value:', dateInput.value);
        
        // Add event listener for date changes with debug
        dateInput.addEventListener('change', function(event) {
            console.log('[Availability] 📅 DATE CHANGED EVENT TRIGGERED!');
            console.log('[Availability] New date value:', event.target.value);
            checkAvailabilityForDate();
        });
        
        // Check initial availability for today
        console.log('[Availability] 🔍 Running initial availability check...');
        checkAvailabilityForDate();
        
        console.log('[Availability] ✅ Real-time availability checker initialized');
    } else {
        console.error('[Availability] ❌ Date input not found!');
        console.error('[Availability] Expected ID:', AVAILABILITY_CONFIG.elements.dateInput);
        // Debug: show all date inputs
        const allDateInputs = document.querySelectorAll('input[type="date"]');
        console.log('[Availability] 🔍 All date inputs found:', allDateInputs);
        allDateInputs.forEach((input, index) => {
            console.log(`[Availability] Date input ${index}:`, input.id, input.name, input);
        });
    }
}

// Make essential functions globally accessible
window.goToProductDetail = goToProductDetail;
window.addToCartQuick = addToCartQuick;
window.checkAvailabilityForDate = checkAvailabilityForDate;
window.initAvailabilityChecker = initAvailabilityChecker;
window.updateFormElements = updateFormElements;
window.updateStockInfo = updateStockInfo;
</script>
@endpush
