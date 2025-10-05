@extends('layouts.marketplace')

@section('content')
<style>
/* Enhanced Product Card Styles */
.product-card-simple {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
}

.product-card-simple:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    border-color: #3b82f6;
}

.product-image-simple {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
    background-color: #f8f9fa;
    /* Perfect image fitting */
    min-height: 100%;
    max-height: 100%;
    display: block;
    border-radius: 0;
    /* Ensure sharp image rendering */
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

.product-card-simple:hover .product-image-simple {
    transform: scale(1.05);
}

/* Container dengan aspect ratio tetap */
.product-image-container {
    position: relative;
    overflow: hidden;
    background-color: #f8f9fa;
    width: 100%;
    /* Aspect ratio 4:3 untuk konsistensi */
    aspect-ratio: 4/3;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Fallback untuk browser yang tidak support aspect-ratio */
@supports not (aspect-ratio: 4/3) {
    .product-image-container {
        height: 0;
        padding-bottom: 75%; /* 4:3 aspect ratio = 75% */
    }
    
    .product-image-container .product-image-simple,
    .product-image-container .product-image-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
}

/* Placeholder dengan aspect ratio sama */
.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #64748b;
    transition: all 0.3s ease;
}

/* Image loading states */
.product-image-simple[data-loading="true"] {
    background: linear-gradient(90deg, #f0f0f0 25%, transparent 37%, #f0f0f0 63%);
    background-size: 400% 100%;
    animation: loading 1.4s ease-in-out infinite;
}

@keyframes loading {
    0% {
        background-position: 100% 50%;
    }
    100% {
        background-position: -100% 50%;
    }
}

/* Lazy loading placeholder */
.image-placeholder-loading {
    background: linear-gradient(45deg, #f8f9fa 25%, #e9ecef 25%, #e9ecef 50%, #f8f9fa 50%, #f8f9fa 75%, #e9ecef 75%);
    background-size: 20px 20px;
    animation: loading-pattern 1s linear infinite;
}

@keyframes loading-pattern {
    0% {
        background-position: 0 0;
    }
    100% {
        background-position: 20px 20px;
    }
}

/* Responsive image container */
.product-image-container {
    position: relative;
    overflow: hidden;
    background-color: #f8f9fa;
}

.product-image-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(0,0,0,0.02) 100%);
    z-index: 1;
}

/* Placeholder for missing images */
.product-image-placeholder {
    height: 220px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #64748b;
    transition: all 0.3s ease;
}

.product-card-simple:hover .product-image-placeholder {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    transform: scale(1.02);
}

/* Container max width for better content focus */
.container {
    max-width: 1200px;
}

/* Image optimization for perfect fit - using aspect ratio */
.product-image-simple,
.product-image-placeholder {
    width: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    border-radius: 0;
}

/* Ensure maximum 4 columns on large screens */
@media (min-width: 992px) {
    #productGrid .col-lg-3 {
        max-width: 25%;
        flex: 0 0 25%;
    }
}

/* Product Grid Container - Maximum 4 columns */
#productGrid {
    max-width: 100%;
}

#productGrid .product-item {
    margin-bottom: 1.5rem;
}

/* Category Card Responsive - Maximum 4 columns */
.category-card-simple {
    transition: all 0.3s ease;
    border-radius: 12px;
    cursor: pointer;
    min-height: 120px;
}

.category-card-simple:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

@media (max-width: 576px) {
    .category-card-simple {
        min-height: 100px;
    }
    
    .category-card-simple .card-body {
        padding: 1rem !important;
    }
    
    .category-card-simple i {
        font-size: 1.25rem !important;
    }
}

@media (min-width: 992px) {
    /* Ensure exactly 4 categories per row on desktop */
    .category-card-simple .col-lg-3 {
        flex: 0 0 25%;
        max-width: 25%;
    }
}

/* Hero Section Responsive */
.hero-modern {
    padding: 3rem 0;
}

@media (max-width: 576px) {
    .hero-modern {
        padding: 2rem 0;
    }
    
    .hero-modern .display-4 {
        font-size: 1.75rem;
    }
    
    .hero-modern .lead {
        font-size: 0.95rem;
    }
}

@media (min-width: 577px) and (max-width: 768px) {
    .hero-modern {
        padding: 2.5rem 0;
    }
    
    .hero-modern .display-4 {
        font-size: 2.25rem;
    }
}

/* Filter buttons responsive */
.filter-container {
    overflow-x: auto;
    padding-bottom: 10px;
}

.filter-container::-webkit-scrollbar {
    height: 6px;
}

.filter-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.filter-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.filter-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@media (max-width: 768px) {
    .btn-group {
        flex-wrap: nowrap;
        white-space: nowrap;
    }
    
    .btn-group .btn {
        flex-shrink: 0;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
}

/* Stats section responsive */
@media (max-width: 576px) {
    .stats-card {
        margin-bottom: 1rem;
        padding: 1.5rem 1rem;
    }
    
    .stats-card h3 {
        font-size: 1.5rem;
    }
    
    .stats-card .lead {
        font-size: 0.9rem;
    }
}

/* Search bar hero responsive */
@media (max-width: 576px) {
    .hero-modern .input-group {
        flex-direction: column;
    }
    
    .hero-modern .form-control {
        border-radius: 25px !important;
        margin-bottom: 0.5rem;
    }
    
    .hero-modern .btn {
        border-radius: 25px !important;
    }
}

/* Prevent button events from bubbling to card */
.product-card-simple .btn,
.product-card-simple .wishlist-btn {
    position: relative;
    z-index: 10;
}

/* Category card hover effect */
.category-card-simple {
    transition: all 0.3s ease;
    border-radius: 12px;
    cursor: pointer;
}

.category-card-simple:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

/* Hero section enhancement */
.hero-modern {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    position: relative;
    overflow: hidden;
}

.hero-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50" cy="50" r="50"><stop offset="0" stop-color="white" stop-opacity="0.1"/><stop offset="1" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="50" cy="10" r="10" fill="url(%23a)"/></svg>') repeat;
    opacity: 0.1;
}

/* Improved wishlist button */
.wishlist-btn {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: none !important;
}

.wishlist-btn:hover {
    transform: scale(1.1);
}

.wishlist-btn.btn-danger {
    background-color: #dc3545 !important;
    color: white !important;
}

/* Product details on hover */
.product-card-simple:hover .card-body {
    background-color: rgba(59, 130, 246, 0.02);
}

/* Animation for search results */
.product-item {
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .product-card-simple:hover {
        transform: translateY(-4px);
    }
    
    .category-card-simple:hover {
        transform: translateY(-3px);
    }
}
</style>

<!-- Hero Section - Simplified -->
<section class="hero-modern">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <div class="animate-fade-in-up">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Peminjaman Alat-Alat Media
                        <span class="text-warning">Mudah & Terpercaya</span>
                    </h1>
                    <p class="lead text-light mb-4">
                        Platform penyewaan alat media terlengkap dengan {{ $totalBarang }}+ produk berkualitas untuk segala kebutuhan produksi Anda
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       class="form-control search-bar-modern" 
                                       placeholder="Cari produk yang ingin disewa..." 
                                       id="heroSearch" 
                                       value="{{ request('search') }}"
                                       autocomplete="off"
                                       spellcheck="false"
                                       autocorrect="off"
                                       autocapitalize="off"
                                       data-lpignore="true"
                                       data-form-type="other">
                                <button class="btn btn-warning fw-bold" type="button" onclick="performSearch()">
                                    <i class="fas fa-search me-2 text-white"></i>
                                    <span class="text-white">Cari Sekarang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-box text-primary me-2 fs-4"></i>
                    <div>
                        <h5 class="mb-0">{{ $totalBarang }}+</h5>
                        <small class="text-muted">Produk Tersedia</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-users text-success me-2 fs-4"></i>
                    <div>
                        <h5 class="mb-0">{{ $totalCustomers }}+</h5>
                        <small class="text-muted">Pelanggan Puas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-circle text-warning me-2 fs-4"></i>
                    <div>
                        <h5 class="mb-0">{{ $totalOrders }}+</h5>
                        <small class="text-muted">Transaksi Sukses</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories - Simplified -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="fw-bold mb-3">Kategori Produk</h3>
                <p class="text-muted">Pilih kategori sesuai kebutuhan Anda</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row g-3">
                    @foreach($jenisBarang as $jenis)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 category-card-simple" data-category="{{ $jenis->id }}">
                            <div class="card-body text-center p-3 p-md-4">
                                <div class="mb-2 mb-md-3">
                                    <i class="fas fa-layer-group text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-1 small">{{ $jenis->nama }}</h6>
                                <small class="text-muted">{{ $jenis->barang_count ?? 0 }} produk</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section - Focus Area -->
<section class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-lg-6">
                @if(request('search'))
                    <h3 class="fw-bold mb-2">Hasil Pencarian</h3>
                    <p class="text-muted mb-0">
                        Menampilkan hasil untuk "<strong>{{ request('search') }}</strong>" 
                        <span class="badge bg-primary ms-2">{{ $barang->count() }} produk</span>
                    </p>
                    @if($barang->count() > 0)
                        <div class="mt-2">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Hapus Filter
                            </a>
                        </div>
                    @endif
                @else
                    <h3 class="fw-bold mb-2">Produk Unggulan</h3>
                    <p class="text-muted mb-0">Koleksi terbaik dan paling diminati</p>
                @endif
            </div>
            <div class="col-lg-6">
                @if(!request('search'))
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <input type="radio" class="btn-check" name="categoryFilter" id="all" autocomplete="off" checked>
                    <label class="btn btn-outline-primary btn-sm" for="all">Semua</label>
                    
                    @foreach($jenisBarang->take(3) as $jenis)
                    <input type="radio" class="btn-check" name="categoryFilter" id="category-{{ $jenis->id }}" autocomplete="off">
                    <label class="btn btn-outline-primary btn-sm" for="category-{{ $jenis->id }}">{{ Str::limit($jenis->nama, 10) }}</label>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="row g-4" id="productGrid">
            @forelse($barang as $item)
            @php
                $encryptedId = \App\Helpers\UrlCrypt::encrypt($item->id);
                $detailUrl = route('peminjaman.detail', $encryptedId);
            @endphp
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item" data-category="category-{{ $item->jenis_barang_id }}" data-encrypted-id="{{ $encryptedId }}">
                <div class="card border-0 shadow-sm h-100 product-card-simple" onclick="goToDetailDirect('{{ $detailUrl }}')" style="cursor: pointer;">
                    <div class="position-relative overflow-hidden product-image-container" onclick="goToDetailDirect('{{ $detailUrl }}')">
                        @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 class="product-image-simple" 
                                 alt="{{ $item->nama }}" 
                                 loading="lazy"
                                 decoding="async"
                                 onload="this.style.opacity=1"
                                 onerror="this.closest('.product-image-container').innerHTML='<div class=&quot;product-image-placeholder&quot;><div class=&quot;text-center&quot;><i class=&quot;fas fa-image mb-2&quot; style=&quot;font-size: 2.5rem;&quot;></i><div class=&quot;small&quot;>Gambar tidak tersedia</div></div></div>'"
                                 style="opacity: 0; transition: opacity 0.3s ease;">
                        @else
                            <div class="product-image-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-image mb-2" style="font-size: 2.5rem;"></i>
                                    <div class="small">Gambar tidak tersedia</div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Status Badges -->
                        <div class="position-absolute top-0 end-0 p-2">
                            @if($item->stok < 5 && $item->stok > 0)
                                <span class="badge bg-warning text-dark small">Terbatas</span>
                            @elseif($item->stok == 0)
                                <span class="badge bg-danger small">Habis</span>
                            @else
                                <span class="badge bg-success small">Tersedia</span>
                            @endif
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="position-absolute top-0 start-0 p-2">
                            <button class="btn btn-sm btn-light rounded-circle wishlist-btn" onclick="toggleWishlist({{ $item->id }}, event)" title="Tambah ke favorit">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-3" onclick="goToDetail({{ $item->id }})">
                        <div class="mb-2">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $item->nama }}</h6>
                            <small class="text-muted">{{ $item->jenisBarang->nama }}</small>
                        </div>
                        
                        @if($item->deskripsi)
                        <p class="text-muted small mb-3 description-text">{{ Str::limit($item->deskripsi, 60) }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-bold text-primary">Rp {{ number_format($item->harga_hari, 0, ',', '.') }}</div>
                                <small class="text-muted">per hari</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Stok: {{ $item->stok }}</small>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <div class="btn-group">
                                <a href="{{ route('peminjaman.show', $item->id) }}" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                @if($item->stok > 0)
                                <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation(); addToCart({{ $item->id }});">
                                    <i class="fas fa-cart-plus me-1"></i>Sewa
                                </button>
                                @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled onclick="event.stopPropagation();">
                                    <i class="fas fa-times me-1"></i>Habis
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        @if(request('search'))
                            <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        @else
                            <i class="fas fa-box-open text-muted" style="font-size: 4rem;"></i>
                        @endif
                    </div>
                    @if(request('search'))
                        <h4 class="text-muted mb-2">Tidak Ada Hasil</h4>
                        <p class="text-muted mb-3">Pencarian untuk "<strong>{{ request('search') }}</strong>" tidak ditemukan.</p>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Semua Produk
                        </a>
                    @else
                        <h4 class="text-muted mb-2">Belum Ada Produk</h4>
                        <p class="text-muted">Produk akan segera hadir. Silakan kembali lagi nanti.</p>
                    @endif
                </div>
            </div>
            @endforelse
        </div>
        
        @if($barang->hasMorePages() && $barang->total() > 20)
        <div class="text-center mt-5" id="loadMoreContainer">
            <button type="button" class="btn btn-outline-primary btn-lg" id="loadMoreBtn" onclick="loadMoreProducts()">
                <i class="fas fa-plus me-2" id="loadMoreIcon"></i>
                <span id="loadMoreText">Muat Lebih Banyak</span>
            </button>
            <div class="mt-2 small text-muted" id="loadMoreInfo">
                Menampilkan {{ $barang->count() }} dari {{ $barang->total() }} produk
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Why Choose Us - Simplified -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="fw-bold mb-4">Mengapa Memilih LonikaRent?</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-shield-alt text-success fs-4"></i>
                            </div>
                            <h6 class="fw-bold">100% Terpercaya</h6>
                            <small class="text-muted">Transaksi aman dan terjamin</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-bolt text-primary fs-4"></i>
                            </div>
                            <h6 class="fw-bold">Proses Cepat</h6>
                            <small class="text-muted">Booking hingga penyewaan</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-headset text-warning fs-4"></i>
                            </div>
                            <h6 class="fw-bold">Support 24/7</h6>
                            <small class="text-muted">Siap membantu kapan saja</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Ensure showNotification function is available
    if (typeof window.showNotification !== 'function') {
        window.showNotification = function(message, type = 'info', duration = 4000) {
            alert(message); // Fallback to simple alert
        };
    }
    
    // Search functionality
    window.performSearch = function() {
        const heroSearch = document.getElementById('heroSearch');
        const searchTerm = heroSearch ? heroSearch.value.trim() : '';
        
        if (searchTerm) {
            window.location.href = `{{ route('peminjaman.index') }}?search=${encodeURIComponent(searchTerm)}#productGrid`;
        } else {
            window.location.href = `{{ route('peminjaman.index') }}#productGrid`;
        }
    };
    
    // Navigate to product detail page directly (optimized)
    window.goToDetailDirect = function(url) {
        if (url) {
            window.location.href = url;
        }
    };
    
    // Navigate to product detail page (legacy)
    window.goToDetail = function(productId) {
        if (productId) {
            window.location.href = `{{ url('/peminjaman/show') }}/${productId}`;
        }
    };
    
    // Handle Enter key in hero search
    document.addEventListener('DOMContentLoaded', function() {
        const heroSearch = document.getElementById('heroSearch');
        if (heroSearch) {
            heroSearch.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }
    });
    
    // Product filtering by category
    $('input[name="categoryFilter"]').change(function() {
        const selectedCategory = $(this).attr('id');
        const $products = $('.product-item');
        
        $products.fadeOut(200, function() {
            if (selectedCategory === 'all') {
                $products.fadeIn(200);
            } else {
                $products.filter('[data-category="' + selectedCategory + '"]').fadeIn(200);
            }
        });
    });
    
    // Category card click to filter
    $('.category-card-simple[data-category]').click(function() {
        const categoryId = $(this).data('category');
        $('#category-' + categoryId).prop('checked', true).trigger('change');
        
        $('html, body').animate({
            scrollTop: $('#productGrid').offset().top - 100
        }, 800);
    });
    
    // Product search
    $('#heroSearch, #searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const $products = $('.product-item');
        
        $products.each(function() {
            const productName = $(this).find('h6').text().toLowerCase();
            const productDesc = $(this).find('.description-text').text().toLowerCase();
            
            if (productName.includes(searchTerm) || productDesc.includes(searchTerm)) {
                $(this).fadeIn(200);
            } else {
                $(this).fadeOut(200);
            }
        });
        
        // Sync search inputs
        const otherSearchInput = $(this).attr('id') === 'heroSearch' ? '#searchInput' : '#heroSearch';
        $(otherSearchInput).val($(this).val());
    });
    
    // Wishlist toggle using session-based system
    window.toggleWishlist = function(itemId, event) {
        event.preventDefault();
        event.stopPropagation();
        
        const button = event.target.closest('button');
        const icon = button.querySelector('i');
        
        // Check CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            if (typeof window.showNotification === 'function') {
                window.showNotification('Token keamanan tidak ditemukan', 'error');
            } else {
                alert('Token keamanan tidak ditemukan');
            }
            return;
        }
        
        // Add to wishlist (for now, we'll always add - can be enhanced to check if already exists)
        fetch('/peminjaman/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                barang_id: parseInt(itemId)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button appearance
                icon.classList.remove('far');
                icon.classList.add('fas');
                button.classList.remove('btn-light');
                button.classList.add('btn-danger');
                
                // Update wishlist count
                if (typeof window.updateWishlistCount === 'function') {
                    window.updateWishlistCount();
                }
                
                if (typeof window.showNotification === 'function') {
                    window.showNotification(data.message || 'Ditambahkan ke favorit', 'success');
                } else {
                    alert(data.message || 'Ditambahkan ke favorit');
                }
            } else {
                if (typeof window.showNotification === 'function') {
                    window.showNotification(data.message || 'Gagal menambahkan ke wishlist', 'warning');
                } else {
                    alert(data.message || 'Produk sudah ada di favorit');
                }
            }
        })
        .catch(error => {
            console.error('Error toggling wishlist:', error);
            if (typeof window.showNotification === 'function') {
                window.showNotification('Terjadi kesalahan saat menambahkan ke wishlist', 'error');
            } else {
                alert('Terjadi kesalahan');
            }
        });
    };
    
    // Load more products function with AJAX
    var currentPage = {{ $barang->currentPage() }};
    var isLoading = false;
    
    window.loadMoreProducts = function() {
        if (isLoading) return;
        
        isLoading = true;
        var loadMoreBtn = document.getElementById('loadMoreBtn');
        var loadMoreIcon = document.getElementById('loadMoreIcon');
        var loadMoreText = document.getElementById('loadMoreText');
        var loadMoreInfo = document.getElementById('loadMoreInfo');
        
        // Update button state
        loadMoreBtn.disabled = true;
        loadMoreIcon.className = 'fas fa-spinner fa-spin me-2';
        loadMoreText.textContent = 'Memuat...';
        
        // Get current search and category filters
        var searchParam = new URLSearchParams(window.location.search).get('search') || '';
        var categoryParam = new URLSearchParams(window.location.search).get('category') || '';
        
        // Prepare AJAX request
        var nextPage = currentPage + 1;
        var url = new URL('{{ route('peminjaman.load-more') }}', window.location.origin);
        url.searchParams.append('page', nextPage);
        if (searchParam) url.searchParams.append('search', searchParam);
        if (categoryParam) url.searchParams.append('category', categoryParam);
        
        fetch(url)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success && data.html) {
                    // Append new products to grid
                    var productGrid = document.getElementById('productGrid');
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    
                    // Add animation to new items
                    var newItems = tempDiv.querySelectorAll('.product-item');
                    newItems.forEach(function(item, index) {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(30px)';
                        productGrid.appendChild(item);
                        
                        // Animate in with delay
                        setTimeout(function() {
                            item.style.transition = 'all 0.6s ease';
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                    
                    currentPage = data.currentPage;
                    
                    // Update info text
                    var totalLoaded = document.querySelectorAll('#productGrid .product-item').length;
                    loadMoreInfo.textContent = 'Menampilkan ' + totalLoaded + ' dari ' + data.totalItems + ' produk';
                    
                    // Check if there are more pages
                    if (!data.hasMore) {
                        var loadMoreContainer = document.getElementById('loadMoreContainer');
                        loadMoreContainer.innerHTML = '<div class="text-center text-muted">Semua produk telah dimuat</div>';
                    } else {
                        // Reset button state
                        loadMoreBtn.disabled = false;
                        loadMoreIcon.className = 'fas fa-plus me-2';
                        loadMoreText.textContent = 'Muat Lebih Banyak';
                    }
                    
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.loadedItems + ' produk berhasil dimuat', 'success', 2000);
                    }
                } else {
                    // Reset button state on error
                    loadMoreBtn.disabled = false;
                    loadMoreIcon.className = 'fas fa-plus me-2';
                    loadMoreText.textContent = 'Muat Lebih Banyak';
                    
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message || 'Gagal memuat produk', 'error');
                    } else {
                        alert(data.message || 'Gagal memuat produk');
                    }
                }
                
                isLoading = false;
            })
            .catch(function(error) {
                console.error('Load more error:', error);
                
                // Reset button state
                loadMoreBtn.disabled = false;
                loadMoreIcon.className = 'fas fa-plus me-2';
                loadMoreText.textContent = 'Muat Lebih Banyak';
                
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Terjadi kesalahan saat memuat produk', 'error');
                } else {
                    alert('Terjadi kesalahan saat memuat produk');
                }
                
                isLoading = false;
            });
    };
    
    // Initialize on page load - no longer needed with session-based wishlist
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });
});

// Define addToCart function globally outside jQuery ready block
window.addToCart = function(barangId, quantity = 1) {
    console.log('Adding to cart from index page (global):', barangId, quantity);
    
    // Validate inputs
    if (!barangId || barangId <= 0) {
        if (typeof window.showNotification === 'function') {
            window.showNotification('ID barang tidak valid', 'error');
        } else {
            alert('ID barang tidak valid');
        }
        return;
    }
    
    if (!quantity || quantity <= 0) {
        quantity = 1;
    }
    
    // Check CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        if (typeof window.showNotification === 'function') {
            window.showNotification('Token keamanan tidak ditemukan', 'error');
        } else {
            alert('Token keamanan tidak ditemukan');
        }
        return;
    }
    
    // Show loading state
    let loadingMsg = null;
    if (typeof window.showNotification === 'function') {
        loadingMsg = window.showNotification('Menambahkan ke keranjang...', 'info', 1000);
    }
    
    fetch('/peminjaman/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            barang_id: parseInt(barangId),
            quantity: parseInt(quantity)
        })
    })
    .then(response => {
        // Remove loading message
        if (loadingMsg && loadingMsg.parentElement) {
            loadingMsg.remove();
        }
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Add to cart response from index (global):', data);
        
        if (data.success) {
            // Update cart badge immediately
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge && data.cart_count !== undefined) {
                cartBadge.textContent = data.cart_count;
                cartBadge.style.display = 'flex';
                cartBadge.classList.remove('d-none');
            }
            
            // Show success notification
            if (typeof window.showNotification === 'function') {
                window.showNotification(data.message || 'Produk berhasil ditambahkan ke keranjang', 'success');
            } else {
                alert(data.message || 'Produk berhasil ditambahkan ke keranjang');
            }
            
            // Update cart count after a short delay
            setTimeout(() => {
                if (typeof window.updateCartCount === 'function') {
                    window.updateCartCount();
                }
            }, 500);
        } else {
            if (typeof window.showNotification === 'function') {
                window.showNotification(data.message || 'Gagal menambahkan ke keranjang', 'error');
            } else {
                alert(data.message || 'Gagal menambahkan ke keranjang');
            }
        }
    })
    .catch(error => {
        console.error('Error adding to cart from index (global):', error);
        
        // Remove loading message if still exists
        if (loadingMsg && loadingMsg.parentElement) {
            loadingMsg.remove();
        }
        
        if (typeof window.showNotification === 'function') {
            window.showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        } else {
            alert('Terjadi kesalahan saat menambahkan ke keranjang');
        }
    });
};

// Prevent search input history/autocomplete
document.addEventListener('DOMContentLoaded', function() {
    // === IMAGE OPTIMIZATION ===
    
    // Enhanced lazy loading with intersection observer
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    img.style.opacity = '1';
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.1
        });

        // Observe all lazy images
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Image loading optimization
    function optimizeImageLoading() {
        const images = document.querySelectorAll('.product-image-simple');
        
        images.forEach(img => {
            // Add loading placeholder
            const container = img.closest('.product-image-container');
            if (container && !img.complete) {
                container.classList.add('image-placeholder-loading');
            }

            // Handle image load
            img.addEventListener('load', function() {
                this.style.opacity = '1';
                const container = this.closest('.product-image-container');
                if (container) {
                    container.classList.remove('image-placeholder-loading');
                }
            });

            // Handle image error
            img.addEventListener('error', function() {
                const container = this.closest('.product-image-container');
                if (container) {
                    container.innerHTML = `
                        <div class="product-image-placeholder">
                            <div class="text-center">
                                <i class="fas fa-image mb-2" style="font-size: 2.5rem;"></i>
                                <div class="small">Gambar tidak tersedia</div>
                            </div>
                        </div>
                    `;
                }
            });
        });
    }

    // Preload critical images
    function preloadCriticalImages() {
        const criticalImages = document.querySelectorAll('.product-image-simple');
        const preloadCount = Math.min(4, criticalImages.length); // Preload first 4 images
        
        for (let i = 0; i < preloadCount; i++) {
            const img = criticalImages[i];
            if (img && img.src) {
                const preloadImg = new Image();
                preloadImg.src = img.src;
            }
        }
    }

    // Initialize optimizations
    optimizeImageLoading();
    preloadCriticalImages();
    
    // === SEARCH INPUT OPTIMIZATION ===
    const searchInputs = document.querySelectorAll('#heroSearch, input[type="text"]');
    searchInputs.forEach(function(input) {
        if (input.placeholder && input.placeholder.includes('Cari')) {
            // Prevent browser autocomplete
            input.setAttribute('data-lpignore', 'true');
            input.setAttribute('data-form-type', 'other');
            
            // Clear input on focus if empty
            input.addEventListener('focus', function() {
                if (!this.value || this.value.trim() === '') {
                    this.value = '';
                }
            });
        }
    });
});
</script>
@endpush
