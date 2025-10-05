@extends('layouts.marketplace')

@push('styles')
<style>
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.wishlist-item {
    transition: all 0.3s ease;
}

.wishlist-remove-btn {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.wishlist-remove-btn:hover {
    transform: scale(1.1);
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-heart text-danger me-2"></i>Wishlist Saya
                    </h2>
                    <p class="text-muted mb-0">Kumpulan produk favorit yang ingin Anda sewa</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-danger rounded-pill fs-6 px-3 py-2">
                        {{ count($wishlistItems) }} produk
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(count($wishlistItems) > 0)
        <!-- Wishlist Items -->
        <div class="row g-4" id="wishlistGrid">
            @foreach($wishlistItems as $item)
            <div class="col-lg-3 col-md-6 wishlist-item" data-id="{{ $item->id }}">
                <div class="card border-0 shadow-sm h-100 product-card-simple">
                    <div class="position-relative overflow-hidden">
                        @if($item->gambar && \Storage::disk('public')->exists($item->gambar))
                            <img src="{{ \Storage::disk('public')->url($item->gambar) }}" class="card-img-top product-image-simple" alt="{{ $item->nama }}">
                        @else
                            <div class="card-img-top product-image-simple d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-image text-muted" style="font-size: 2.5rem;"></i>
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
                        
                        <!-- Remove from Wishlist Button -->
                        <div class="position-absolute top-0 start-0 p-2">
                            <button class="btn btn-sm btn-danger rounded-circle wishlist-remove-btn" onclick="removeFromWishlist({{ $item->id }}, event)" title="Hapus dari favorit">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $item->nama }}</h6>
                            <small class="text-muted">{{ $item->jenisBarang->nama }}</small>
                        </div>
                        
                        @if($item->deskripsi)
                        <p class="card-text text-muted small description-text mb-2">{{ Str::limit($item->deskripsi, 80) }}</p>
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
                                <a href="{{ route('peminjaman.show', $item->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                @if($item->stok > 0)
                                <button type="button" class="btn btn-primary btn-sm" onclick="addToCart({{ $item->id }})">
                                    <i class="fas fa-cart-plus me-1"></i>Sewa
                                </button>
                                @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-times me-1"></i>Habis
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <button type="button" class="btn btn-danger" onclick="clearWishlist()">
                        <i class="fas fa-trash me-2"></i>Kosongkan Wishlist
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                    </a>
                    <a href="{{ route('peminjaman.cart') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Lihat Keranjang
                    </a>
                </div>
            </div>
        </div>

    @else
        <!-- Empty Wishlist -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-heart-broken text-muted" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Wishlist Kosong</h4>
                    <p class="text-muted mb-4">Anda belum menambahkan produk ke wishlist. Mulai jelajahi produk dan tambahkan yang Anda sukai!</p>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-store me-2"></i>Jelajahi Produk
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function removeFromWishlist(barangId, event) {
    event.preventDefault();
    event.stopPropagation();
    
    if (!confirm('Hapus produk ini dari wishlist?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    // Show loading animation
    const wishlistItem = document.querySelector(`.wishlist-item[data-id="${barangId}"]`);
    if (wishlistItem) {
        wishlistItem.style.opacity = '0.6';
        wishlistItem.style.pointerEvents = 'none';
    }
    
    fetch(`/peminjaman/wishlist/${barangId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animate item removal
            if (wishlistItem) {
                wishlistItem.style.transition = 'all 0.5s ease';
                wishlistItem.style.transform = 'scale(0.8) translateY(-20px)';
                wishlistItem.style.opacity = '0';
                
                setTimeout(() => {
                    wishlistItem.remove();
                    
                    // Check if wishlist is now empty
                    const remainingItems = document.querySelectorAll('.wishlist-item');
                    if (remainingItems.length === 0) {
                        showNotification('Wishlist sekarang kosong, memuat ulang halaman...', 'info', 2000);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        // Update count badge
                        updateCountBadge(data.wishlist_count);
                    }
                }, 500);
            }
            
            // Update wishlist count in navigation
            if (typeof updateWishlistCount === 'function') {
                updateWishlistCount();
            }
            
            // Show success message with animation
            showSuccessAnimation('Produk berhasil dihapus dari wishlist!');
        } else {
            // Restore item if failed
            if (wishlistItem) {
                wishlistItem.style.opacity = '1';
                wishlistItem.style.pointerEvents = 'auto';
            }
            showNotification(data.message || 'Gagal menghapus dari wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error removing from wishlist:', error);
        // Restore item if failed
        if (wishlistItem) {
            wishlistItem.style.opacity = '1';
            wishlistItem.style.pointerEvents = 'auto';
        }
        showNotification('Terjadi kesalahan saat menghapus dari wishlist', 'error');
    });
}

function clearWishlist() {
    if (!confirm('Kosongkan semua item dari wishlist?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    // Show loading state
    const allItems = document.querySelectorAll('.wishlist-item');
    allItems.forEach(item => {
        item.style.opacity = '0.6';
        item.style.pointerEvents = 'none';
    });
    
    fetch('/peminjaman/wishlist/clear', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animate all items removal
            allItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.transform = 'scale(0.8) translateY(-20px)';
                    item.style.opacity = '0';
                }, index * 100); // Stagger animation
            });
            
            // Show success message and reload
            showSuccessAnimation('Wishlist berhasil dikosongkan!');
            setTimeout(() => {
                location.reload();
            }, 1500);
            
            // Update wishlist count in navigation
            if (typeof updateWishlistCount === 'function') {
                updateWishlistCount();
            }
        } else {
            // Restore items if failed
            allItems.forEach(item => {
                item.style.opacity = '1';
                item.style.pointerEvents = 'auto';
            });
            showNotification(data.message || 'Gagal mengosongkan wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error clearing wishlist:', error);
        // Restore items if failed
        allItems.forEach(item => {
            item.style.opacity = '1';
            item.style.pointerEvents = 'auto';
        });
        showNotification('Terjadi kesalahan saat mengosongkan wishlist', 'error');
    });
}

function showSuccessAnimation(message) {
    // Create success animation element
    const successEl = document.createElement('div');
    successEl.innerHTML = `
        <div class="position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
            <div class="bg-success text-white rounded-3 px-4 py-3 shadow-lg">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-white me-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <i class="fas fa-check-circle me-2 fs-4"></i>
                    <span class="fw-semibold">${message}</span>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(successEl);
    
    // Animate in
    successEl.style.opacity = '0';
    successEl.style.transform = 'scale(0.8)';
    successEl.style.transition = 'all 0.3s ease';
    
    setTimeout(() => {
        successEl.style.opacity = '1';
        successEl.style.transform = 'scale(1)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        successEl.style.opacity = '0';
        successEl.style.transform = 'scale(0.8)';
        setTimeout(() => {
            if (successEl.parentElement) {
                successEl.remove();
            }
        }, 300);
    }, 2000);
}

function updateCountBadge(count) {
    const badge = document.querySelector('.badge');
    if (badge) {
        badge.textContent = `${count} produk`;
        
        // Add pulse animation
        badge.style.animation = 'pulse 0.5s ease-in-out';
        setTimeout(() => {
            badge.style.animation = '';
        }, 500);
    }
}

// Make functions globally accessible
window.removeFromWishlist = removeFromWishlist;
window.clearWishlist = clearWishlist;
</script>
@endpush
