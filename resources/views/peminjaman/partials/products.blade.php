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
                    <h3 class="fw-bold mb-2" id="sectionTitle">Produk Unggulan</h3>
                    <p class="text-muted mb-0" id="sectionDescription">Koleksi terbaik dan paling diminati</p>
                @endif
            </div>
            <div class="col-lg-6">
                @if(!request('search'))
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <input type="radio" class="btn-check" name="categoryFilter" id="all" autocomplete="off" checked>
                    <label class="btn btn-outline-primary btn-sm" for="all">Semua</label>
                    
                    @foreach($jenisBarang->where('barang_count', '>', 0)->take(4) as $jenis)
                    <input type="radio" class="btn-check" name="categoryFilter" id="category-{{ $jenis->id }}" autocomplete="off">
                    <label class="btn btn-outline-primary btn-sm" for="category-{{ $jenis->id }}">{{ Str::limit($jenis->nama, 8) }}</label>
                    @endforeach
                    
                    @if($jenisBarang->where('barang_count', '>', 0)->count() > 4)
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="showMoreCategories" onclick="toggleMoreCategories()">
                        <span id="moreText">+{{ $jenisBarang->where('barang_count', '>', 0)->count() - 4 }} lainnya</span>
                        <i class="fas fa-chevron-down ms-1" id="moreIcon"></i>
                    </button>
                    @endif
                    
                    <!-- Container untuk kategori tambahan -->
                    <div id="moreCategoriesContainer" class="d-none w-100 mt-2">
                        <div class="d-flex flex-wrap gap-2" id="moreCategoriesContent">
                            <!-- Kategori tambahan akan dimuat di sini -->
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="row g-4" id="productGrid">
            @forelse($barang as $item)
            @php
                // Generate optimized short URL - much faster than old encryption
                $detailUrl = \App\Helpers\UrlCrypt::shortRoute($item->id);
            @endphp
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item" data-category="{{ $item->jenis_barang_id }}" data-product-id="{{ $item->id }}">
                <div class="card border-0 shadow-sm h-100 product-card-simple" onclick="goToDetailDirect('{{ $detailUrl }}')" style="cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="position-relative overflow-hidden product-image-container" onclick="goToDetailDirect('{{ $detailUrl }}')" style="border-radius: 0.5rem 0.5rem 0 0;">
                        @if($item->gambar && \Storage::disk('public')->exists($item->gambar))
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 class="card-img-top product-image-simple" 
                                 alt="{{ $item->nama }}" 
                                 loading="lazy"
                                 style="height: 200px; object-fit: cover; transition: transform 0.3s ease;"
                                 onload="this.style.opacity='1'; this.closest('.product-image-container').classList.remove('image-loading')"
                                 onerror="this.closest('.product-image-container').innerHTML='<div class=\'product-image-placeholder\'><div class=\'text-center text-muted\'><i class=\'fas fa-image fa-2x mb-2\'></i><div class=\'small\'>Gambar tidak tersedia</div></div></div>'"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div class="product-image-placeholder">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <div class="small">Tidak ada gambar</div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($item->stok == 0)
                                <span class="badge bg-danger">Stok Habis</span>
                            @elseif(($item->available_stock ?? 0) == 0)
                                <span class="badge bg-warning text-dark">Booking Only</span>
                            @elseif(($item->available_stock ?? 0) < 3)
                                <span class="badge bg-success">{{ $item->available_stock ?? 0 }} Tersedia</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="position-absolute top-0 start-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle" onclick="toggleWishlist({{ $item->id }}, event)" title="Tambah ke favorit" style="width: 32px; height: 32px; padding: 0;">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-3">
                        <!-- Product Name & Category -->
                        <div class="mb-2">
                            <h6 class="card-title fw-bold mb-1 text-truncate">{{ $item->nama }}</h6>
                            <small class="text-muted">{{ $item->jenisBarang->nama }}</small>
                        </div>
                        
                        <!-- Description -->
                        @if($item->deskripsi)
                        <p class="card-text text-muted small mb-3" style="height: 2.5em; overflow: hidden; line-height: 1.25;">{{ Str::limit($item->deskripsi, 60) }}</p>
                        @else
                        <div style="height: 2.5em; margin-bottom: 1rem;"></div>
                        @endif
                        
                        <!-- Price -->
                        <div class="mb-3">
                            <div class="h6 text-primary fw-bold mb-0">Rp {{ number_format($item->harga_hari, 0, ',', '.') }}</div>
                            <small class="text-muted">per hari</small>
                        </div>
                        
                        <!-- Stock Info - Simple & Clean -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center py-2 px-3 bg-light rounded">
                                <span class="small text-muted">
                                    <i class="fas fa-box me-1"></i>Stok Total
                                </span>
                                <span class="small fw-semibold">{{ $item->stok }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 px-3 mt-1 rounded {{ ($item->available_stock ?? 0) > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                <span class="small">
                                    <i class="fas fa-calendar-check me-1"></i>Tersedia Hari Ini
                                </span>
                                <span class="small fw-bold">{{ $item->available_stock ?? 0 }}</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <div class="btn-group">
                                <a href="{{ $detailUrl }}" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                @if($item->stok > 0)
                                    @if(($item->available_stock ?? 0) > 0)
                                    <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation(); addToCart({{ $item->id }});">
                                        <i class="fas fa-cart-plus me-1"></i>Sewa
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-warning btn-sm" onclick="event.stopPropagation(); addToCart({{ $item->id }});">
                                        <i class="fas fa-calendar-plus me-1"></i>Booking
                                    </button>
                                    @endif
                                @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-times me-1"></i>Stok Habis
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
        
        {{-- Show total count with category context --}}
        @if($barang->count() > 0)
        <div class="text-center mt-4">
            <div class="small text-muted" id="productCountText">
                @if(request('category') && request('category') !== 'all')
                    @php
                        $categoryName = $jenisBarang->where('id', request('category'))->first()->nama ?? 'Kategori';
                    @endphp
                    Menampilkan {{ $barang->count() }} produk kategori {{ $categoryName }}
                @elseif(request('search'))
                    Ditemukan {{ $barang->count() }} produk untuk "{{ request('search') }}"
                @else
                    Menampilkan {{ $barang->count() }} produk dari semua kategori
                @endif
            </div>
            <div class="small text-muted mt-1">
                <i class="fas fa-clock me-1"></i>
                Diperbarui: {{ now()->format('d M Y H:i') }} WIB
            </div>
        </div>
        @endif
    </div>
</section>

<script>
// Simple add to cart function for product listing
function addToCart(productId) {
    console.log('[Cart] Adding product to cart:', productId);
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showNotification('Error: Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    // Show loading state on button
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    const isBooking = button.classList.contains('btn-warning');
    
    button.disabled = true;
    button.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i>${isBooking ? 'Booking...' : 'Menambah...'}`;
    
    // Send request to add to cart
    fetch('/peminjaman/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            barang_id: productId,
            quantity: 1
            // No date - will be selected at checkout
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('[Cart] Success response:', data);
        
        if (data.success) {
            // Success state
            button.classList.remove('btn-primary', 'btn-warning');
            button.classList.add('btn-success');
            button.innerHTML = `<i class="fas fa-check me-1"></i>${isBooking ? 'Dibooking!' : 'Ditambahkan!'}`;
            
            // Show notification
            if (typeof showNotification === 'function') {
                const message = isBooking ? 
                    'Berhasil dibooking! Pilih tanggal di checkout.' : 
                    data.message || 'Berhasil ditambahkan ke keranjang!';
                showNotification(message, 'success');
            }
            
            // Update cart count if function exists
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.classList.remove('btn-success');
                button.classList.add(isBooking ? 'btn-warning' : 'btn-primary');
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 2000);
            
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('[Cart] Error:', error);
        
        // Error state
        button.classList.remove('btn-primary', 'btn-warning');
        button.classList.add('btn-danger');
        button.innerHTML = '<i class="fas fa-times me-1"></i>Gagal';
        
        // Show notification
        if (typeof showNotification === 'function') {
            showNotification(`Error: ${error.message}`, 'error');
        }
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.classList.remove('btn-danger');
            button.classList.add(isBooking ? 'btn-warning' : 'btn-primary');
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 2000);
    });
}

// Helper function for notifications (fallback if not defined elsewhere)
function showNotification(message, type = 'info') {
    if (window.showNotification) {
        window.showNotification(message, type);
    } else {
        // Simple fallback alert
        alert(message);
    }
}

// Navigation functions
function goToDetailDirect(url) {
    if (url) {
        window.location.href = url;
    }
}

// Fast navigation to product detail using cached URLs
function goToDetail(productId) {
    if (productId && window.productUrls && window.productUrls[productId]) {
        // Use pre-generated URL (fastest method)
        window.location.href = window.productUrls[productId];
    } else {
        // Fallback: generate URL on the fly
        fetch(`/peminjaman/encrypt-url/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.url;
                } else {
                    console.error('Failed to get encrypted URL');
                    window.location.href = `/barang?id=${productId}`; // Simple fallback
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = `/barang?id=${productId}`; // Simple fallback
            });
    }
}

function toggleWishlist(productId, event) {
    event.stopPropagation();
    // TODO: Implement wishlist functionality
    console.log('Wishlist toggle for product:', productId);
}

// Pre-generate URLs for all products on page load for instant navigation
document.addEventListener('DOMContentLoaded', function() {
    const productElements = document.querySelectorAll('.product-item[data-product-id]');
    
    if (productElements.length > 0) {
        const productIds = Array.from(productElements).map(el => el.dataset.productId);
        
        // Pre-generate URLs in batches for better performance
        if (productIds.length > 0) {
            preGenerateProductUrls(productIds);
        }
    }
});

// Pre-generate product URLs using batch API
function preGenerateProductUrls(productIds) {
    if (!productIds || productIds.length === 0) return;
    
    console.log('[URL Cache] Pre-generating URLs for', productIds.length, 'products...');
    
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
            window.productUrls = data.urls;
            console.log('[URL Cache] ✅ Cached', Object.keys(data.urls).length, 'product URLs');
        } else {
            console.warn('[URL Cache] Failed to cache URLs:', data.message);
        }
    })
    .catch(error => {
        console.warn('[URL Cache] Error caching URLs:', error);
    });
}
</script>