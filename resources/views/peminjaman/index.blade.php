@extends('layouts.marketplace')

@section('content')
<style>
/* Essential Product Card Styles with Smooth Transitions */
.product-card-simple {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transform: translateZ(0); /* Enable hardware acceleration */
    will-change: transform;
    backface-visibility: hidden;
}

.product-card-simple:hover {
    transform: translateY(-12px) translateZ(0);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12), 0 8px 16px rgba(0, 0, 0, 0.08);
    border-color: #3b82f6;
}

/* Category Card Styles */
.category-card-simple {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: 2px solid transparent;
    transform: translateZ(0);
}

.category-card-simple:hover {
    transform: translateY(-4px) translateZ(0);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    border-color: var(--bs-primary);
}

.category-card-simple.active {
    border-color: var(--bs-primary);
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.1) 100%);
    transform: translateY(-2px) translateZ(0);
    box-shadow: 0 8px 16px rgba(13, 110, 253, 0.2);
}

/* Product Filtering Animation */
.product-item {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateZ(0);
}

.product-item.filtering-hide {
    display: none !important;
}

.product-item.filtering-show {
    display: block !important;
    opacity: 1;
    transform: scale(1) translateY(0) translateZ(0);
}

.product-image-simple {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    background-color: #f8f9fa;
    min-height: 100%;
    max-height: 100%;
    display: block;
    border-radius: 0;
    /* Smooth image rendering */
    image-rendering: auto;
    image-rendering: -webkit-optimize-contrast;
    opacity: 0;
    transform: scale(1) translateZ(0);
    will-change: transform, opacity;
    backface-visibility: hidden;
}

.product-image-simple.loaded {
    opacity: 1;
    transition: opacity 0.4s ease-out, transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card-simple:hover .product-image-simple {
    transform: scale(1.08) translateZ(0);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    background: linear-gradient(45deg, #f8f9fa 25%, transparent 25%), 
                linear-gradient(-45deg, #f8f9fa 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, #f8f9fa 75%), 
                linear-gradient(-45deg, transparent 75%, #f8f9fa 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    width: 100%;
    aspect-ratio: 4/3;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px 12px 0 0;
    transform: translateZ(0);
}

.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #6c757d;
    border-radius: 0;
    transition: all 0.3s ease;
}

.category-card-simple {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 16px;
    cursor: pointer;
    min-height: 120px;
    transform: translateZ(0);
    will-change: transform;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.category-card-simple:hover {
    transform: translateY(-6px) scale(1.02) translateZ(0);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.1), 0 6px 12px rgba(0, 0, 0, 0.05);
    background: rgba(255, 255, 255, 1);
    border-color: rgba(59, 130, 246, 0.2);
}

.hero-modern {
    background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    position: relative;
    overflow: hidden;
    padding: 3rem 0;
    transform: translateZ(0);
}

.hero-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50" cy="50" r="50"><stop offset="0" stop-color="white" stop-opacity="0.08"/><stop offset="1" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="50" cy="10" r="10" fill="url(%23a)"/></svg>') repeat;
    opacity: 0.6;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(180deg); }
}

.wishlist-btn {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: none !important;
    transform: translateZ(0);
    will-change: transform;
    background: rgba(255, 255, 255, 0.9);
}

.wishlist-btn:hover {
    transform: scale(1.15) translateZ(0);
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.wishlist-btn.btn-danger {
    background: rgba(220, 53, 69, 0.95) !important;
    color: white !important;
}

.wishlist-btn.btn-danger:hover {
    background: rgba(220, 53, 69, 1) !important;
}

.product-item {
    opacity: 0;
    animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.product-item:nth-child(1) { animation-delay: 0.1s; }
.product-item:nth-child(2) { animation-delay: 0.2s; }
.product-item:nth-child(3) { animation-delay: 0.3s; }
.product-item:nth-child(4) { animation-delay: 0.4s; }
.product-item:nth-child(n+5) { animation-delay: 0.5s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Loading placeholder animation */
.image-loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Enhanced focus states */
.product-card-simple:focus-within {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Filter transition */
.product-item.filtering-hide {
    display: none !important;
}

.product-item.filtering-show {
    display: block !important;
    opacity: 1;
    transform: scale(1) translateY(0);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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
@include('peminjaman.partials.scripts')
@include('peminjaman.partials.debug')
@endpush