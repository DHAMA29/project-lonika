@extends('layouts.marketplace')

@section('content')
<style>
/* Essential Product Card Styles */
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
    min-height: 100%;
    max-height: 100%;
    display: block;
    border-radius: 0;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

.product-card-simple:hover .product-image-simple {
    transform: scale(1.05);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    background-color: #f8f9fa;
    width: 100%;
    aspect-ratio: 4/3;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    color: #6c757d;
    border-radius: 0;
}

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

.hero-modern {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    position: relative;
    overflow: hidden;
    padding: 3rem 0;
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
    .category-card-simple {
        min-height: 100px;
    }
    .product-card-simple:hover {
        transform: translateY(-4px);
    }
    .category-card-simple:hover {
        transform: translateY(-3px);
    }
}

@media (min-width: 992px) {
    #productGrid .col-lg-3 {
        max-width: 25%;
        flex: 0 0 25%;
    }
}
</style>

{{-- Include all modular sections --}}
@include('peminjaman.partials.hero')

@include('peminjaman.partials.stats')

@include('peminjaman.partials.categories')

@include('peminjaman.partials.products')

@include('peminjaman.partials.reasons')

@endsection

@push('scripts')
<script>
// Include the JavaScript functionality
@include('peminjaman.partials.scripts')
</script>
@endpush