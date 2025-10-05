<script>
// Global functions - available immediately
window.urlEncryptionCache = new Map();

window.goToDetailDirect = function(url) {
    if (url) {
        window.location.href = url;
    }
};

window.goToDetail = function(productId) {
    if (productId) {
        // Check cache first
        if (window.urlEncryptionCache.has(productId)) {
            const cachedUrl = window.urlEncryptionCache.get(productId);
            window.location.href = cachedUrl;
            return;
        }
        
        // Get encrypted URL dari server (legacy fallback)
        fetch(`{{ url('/peminjaman/encrypt-url') }}/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cache the result
                    window.urlEncryptionCache.set(productId, data.url);
                    window.location.href = data.url;
                } else {
                    console.error('Failed to get encrypted URL');
                    // Fallback ke URL biasa jika gagal
                    window.location.href = `{{ url('/peminjaman/show') }}/${productId}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback ke URL biasa jika gagal
                window.location.href = `{{ url('/peminjaman/show') }}/${productId}`;
            });
    }
};

window.performSearch = function() {
    const heroSearch = document.getElementById('heroSearch');
    const searchTerm = heroSearch ? heroSearch.value.trim() : '';
    
    if (searchTerm) {
        window.location.href = `{{ route('peminjaman.index') }}?search=${encodeURIComponent(searchTerm)}#productGrid`;
    } else {
        window.location.href = `{{ route('peminjaman.index') }}#productGrid`;
    }
};
</script>

<script>
$(document).ready(function() {
    // Ensure showNotification function is available
    if (typeof window.showNotification !== 'function') {
        window.showNotification = function(message, type = 'info', duration = 4000) {
            alert(message); // Fallback to simple alert
        };
    }
    
    // Enhanced Image Loading with Smooth Transitions
    function initSmoothImageLoading() {
        const images = document.querySelectorAll('.product-image-simple');
        
        images.forEach((img, index) => {
            const container = img.closest('.product-image-container');
            
            // Add loading state
            if (container && !img.complete) {
                container.classList.add('image-loading');
                img.style.opacity = '0';
            }
            
            // Handle successful image load
            img.addEventListener('load', function() {
                // Remove loading state
                if (container) {
                    container.classList.remove('image-loading');
                }
                
                // Smooth fade in
                this.classList.add('loaded');
                setTimeout(() => {
                    this.style.opacity = '1';
                }, index * 50); // Stagger the loading animation
            });
            
            // Handle image error
            img.addEventListener('error', function() {
                if (container) {
                    container.classList.remove('image-loading');
                    container.innerHTML = `
                        <div class="product-image-placeholder">
                            <div class="text-center">
                                <i class="fas fa-image mb-2" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                <div class="small text-muted">Gambar tidak tersedia</div>
                            </div>
                        </div>
                    `;
                }
            });
            
            // Force trigger if already loaded
            if (img.complete && img.naturalHeight !== 0) {
                img.dispatchEvent(new Event('load'));
            }
        });
    }
    
    // Note: filterProductsByCategory function moved to global scope below
    
    // Note: Helper functions (updatePageInfo, showErrorMessage, initializeProductCards) moved to global scope below
    
    // Note: setupAjaxCategoryFilters moved to global scope below
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        console.log('Popstate event:', event.state);
        
        if (event.state && event.state.category) {
            const categoryId = event.state.category;
            
            // Update radio button
            $('input[name="categoryFilter"]').prop('checked', false);
            if (categoryId === 'all') {
                $('#all').prop('checked', true);
            } else {
                $('#category-' + categoryId).prop('checked', true);
            }
            
            // Filter without adding to history
            window.filterProductsByCategory(categoryId);
        }
    });
    
    // DEPRECATED: Old smoothProductFilter (kept for compatibility)
    function smoothProductFilter(selectedCategory) {
        console.log('DEPRECATED: smoothProductFilter called, redirecting to AJAX version');
        
        // Extract category ID
        let categoryId = 'all';
        if (selectedCategory !== 'all') {
            categoryId = selectedCategory.replace('category-', '');
        }
        
        window.filterProductsByCategory(categoryId);
    }

    // Initialize smooth image loading
    initSmoothImageLoading();
    
    // Wait for all elements to be ready and setup all handlers
    setTimeout(() => {
        console.log('Setting up all category handlers...');
        
        // Setup AJAX-based category filtering (using global function)
        window.setupAjaxCategoryFilters();
        
        // Category card click to filter with smooth scroll (if they exist)
        $('.category-card-simple[data-category]').off('click.categoryCard').on('click.categoryCard', function() {
            const categoryId = $(this).data('category');
            console.log('Category card clicked:', categoryId); // Debug log
            
            // Add active state
            $('.category-card-simple').removeClass('active');
            $(this).addClass('active');
            
            // Handle category filtering with AJAX
            if (categoryId === 'all') {
                console.log('Filtering: Show all products'); // Debug log
                $('#all').prop('checked', true);
                window.filterProductsByCategory('all');
            } else {
                console.log('Filtering by category:', categoryId); // Debug log
                $('#category-' + categoryId).prop('checked', true);
                window.filterProductsByCategory(categoryId);
            }
            
            // Smooth scroll to products
            if ($('#productGrid').length) {
                $('html, body').animate({
                    scrollTop: $('#productGrid').offset().top - 120
                }, 1000);
            }
        });
        
        console.log('Category handlers set up. Found category cards:', $('.category-card-simple[data-category]').length);
    }, 500);
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
    
    // Navigate to product detail page (legacy fallback)
    window.goToDetail = function(productId) {
        if (productId) {
            // Check cache first
            if (window.urlEncryptionCache && window.urlEncryptionCache.has(productId)) {
                const cachedUrl = window.urlEncryptionCache.get(productId);
                window.location.href = cachedUrl;
                return;
            }
            
            // Get encrypted URL dari server (legacy fallback)
            fetch(`{{ url('/peminjaman/encrypt-url') }}/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cache the result
                        if (window.urlEncryptionCache) {
                            window.urlEncryptionCache.set(productId, data.url);
                        }
                        window.location.href = data.url;
                    } else {
                        console.error('Failed to get encrypted URL');
                        // Fallback ke URL biasa jika gagal
                        window.location.href = `{{ url('/peminjaman/show') }}/${productId}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Fallback ke URL biasa jika gagal
                    window.location.href = `{{ url('/peminjaman/show') }}/${productId}`;
                });
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
    
    // Enhanced Product search with debouncing
    let searchTimeout;
    $('#heroSearch, #searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Debounce search
        searchTimeout = setTimeout(() => {
            const $products = $('.product-item');
            let visibleCount = 0;
            
            $products.each(function() {
                const $product = $(this);
                const productName = $product.find('h6').text().toLowerCase();
                const productDesc = $product.find('.description-text').text().toLowerCase();
                
                if (productName.includes(searchTerm) || productDesc.includes(searchTerm)) {
                    $product.removeClass('filtering-hide').addClass('filtering-show');
                    $product.fadeIn(400);
                    visibleCount++;
                } else {
                    $product.addClass('filtering-hide').removeClass('filtering-show');
                    $product.fadeOut(400);
                }
            });
            
            // Update product count text based on search
            const $productCountText = $('#productCountText');
            if ($productCountText.length) {
                if (searchTerm.trim() === '') {
                    // No search term - show original text based on current category
                    const currentCategory = window.currentCategoryFilter || 'all';
                    if (currentCategory === 'all') {
                        $productCountText.text('Menampilkan ' + visibleCount + ' produk dari semua kategori');
                    } else {
                        // Get category name from currently active filter button
                        const activeCategoryName = $('.category-filter.active').text() || 'kategori';
                        $productCountText.text('Menampilkan ' + visibleCount + ' produk kategori ' + activeCategoryName);
                    }
                } else {
                    // With search term
                    $productCountText.text('Ditemukan ' + visibleCount + ' produk untuk "' + searchTerm + '"');
                }
            }
            
            // Sync search inputs
            const otherSearchInput = $(this).attr('id') === 'heroSearch' ? '#searchInput' : '#heroSearch';
            $(otherSearchInput).val($(this).val());
        }, 300);
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
    
    // Load more products function - DISABLED (all products loaded at once)
    var currentPage = 1; // Not used anymore since no pagination
    var isLoading = false;
    
    // Initialize current category filter from URL parameter
    window.currentCategoryFilter = new URLSearchParams(window.location.search).get('category') || 'all';
    
    window.loadMoreProducts = function() {
        console.log('Load more disabled - all products are loaded at once');
        return false;
    };
    
    // Initialize on page load - no longer needed with session-based wishlist
    
    // Smooth scrolling for anchor links with easing
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 1000, 'easeInOutCubic');
        }
    });
    
    // Initialize all smooth enhancements
    initSmoothImageLoading();
    
    // Add easing function for jQuery
    $.easing.easeInOutCubic = function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
        return c/2*((t-=2)*t*t + 2) + b;
    };
    
    // Stagger animation for product cards on load
    $('.product-item').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
    // Add hover enhancement for better performance
    $('.product-card-simple').on('mouseenter', function() {
        $(this).addClass('hover-active');
    }).on('mouseleave', function() {
        $(this).removeClass('hover-active');
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
                
                // Add animation to badge
                cartBadge.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    cartBadge.style.transform = 'scale(1)';
                }, 200);
            }
            
            // Success notification
            if (typeof window.showNotification === 'function') {
                window.showNotification(data.message || 'Produk berhasil ditambahkan ke keranjang', 'success');
            } else {
                alert(data.message || 'Produk berhasil ditambahkan ke keranjang');
            }
        } else {
            // Error notification
            if (typeof window.showNotification === 'function') {
                window.showNotification(data.message || 'Gagal menambahkan produk ke keranjang', 'error');
            } else {
                alert(data.message || 'Gagal menambahkan produk ke keranjang');
            }
        }
    })
    .catch(error => {
        console.error('Add to cart error from index (global):', error);
        
        // Remove loading message on error
        if (loadingMsg && loadingMsg.parentElement) {
            loadingMsg.remove();
        }
        
        const errorMessage = 'Terjadi kesalahan saat menambahkan ke keranjang';
        if (typeof window.showNotification === 'function') {
            window.showNotification(errorMessage, 'error');
        } else {
            alert(errorMessage);
        }
    });
};

// Enhanced lazy loading with intersection observer
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const container = img.closest('.product-image-container');
                
                // Add loading state
                if (container) {
                    container.classList.add('image-loading');
                }
                
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                
                // Handle load
                img.addEventListener('load', function() {
                    if (container) {
                        container.classList.remove('image-loading');
                    }
                    this.style.opacity = '1';
                    this.classList.add('loaded');
                });
                
                observer.unobserve(img);
            }
        });
    }, {
        rootMargin: '100px 0px',
        threshold: 0.1
    });

    // Observe all lazy images
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Enhanced image loading optimization
function optimizeImageLoading() {
    const images = document.querySelectorAll('.product-image-simple');
    
    images.forEach((img, index) => {
        const container = img.closest('.product-image-container');
        
        // Add loading placeholder with delay
        if (container && !img.complete) {
            setTimeout(() => {
                container.classList.add('image-loading');
            }, index * 50);
        }

        // Handle image load with smooth transition
        img.addEventListener('load', function() {
            const container = this.closest('.product-image-container');
            if (container) {
                container.classList.remove('image-loading');
            }
            
            // Smooth opacity transition
            this.style.transition = 'opacity 0.5s ease-out';
            this.style.opacity = '1';
            this.classList.add('loaded');
        });

        // Enhanced error handling
        img.addEventListener('error', function() {
            const container = this.closest('.product-image-container');
            if (container) {
                container.classList.remove('image-loading');
                container.innerHTML = `
                    <div class="product-image-placeholder" style="animation: fadeIn 0.5s ease-out;">
                        <div class="text-center">
                            <i class="fas fa-image mb-2" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            <div class="small text-muted">Gambar tidak tersedia</div>
                        </div>
                    </div>
                `;
            }
        });
        
        // Force trigger if already loaded
        if (img.complete && img.naturalHeight !== 0) {
            img.dispatchEvent(new Event('load'));
        }
    });
}

// Optimized preload critical images
function preloadCriticalImages() {
    const criticalImages = document.querySelectorAll('.product-image-simple');
    const preloadCount = Math.min(6, criticalImages.length); // Preload first 6 images
    
    for (let i = 0; i < preloadCount; i++) {
        const img = criticalImages[i];
        if (img && img.src) {
            const preloadImg = new Image();
            preloadImg.onload = function() {
                img.style.opacity = '1';
                img.classList.add('loaded');
            };
            preloadImg.src = img.src;
        }
    }
}

// Initialize optimizations with performance timing
requestAnimationFrame(() => {
    optimizeImageLoading();
    preloadCriticalImages();
});

// === SEARCH INPUT OPTIMIZATION ===
const searchInputs = document.querySelectorAll('#heroSearch, input[type="text"]');
searchInputs.forEach(function(input) {
    if (input.placeholder && input.placeholder.includes('Cari')) {
        // Prevent browser autocomplete
        input.setAttribute('data-lpignore', 'true');
        input.setAttribute('data-form-type', 'other');
        
        // Enhanced focus behavior
        input.addEventListener('focus', function() {
            if (!this.value || this.value.trim() === '') {
                this.value = '';
            }
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    }
});

// Add CSS animations dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .hover-active {
        z-index: 10;
    }
    
    .category-card-simple.active {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        color: white !important;
        transform: translateY(-6px) scale(1.02) translateZ(0) !important;
    }
    
    .category-card-simple.active * {
        color: white !important;
    }
    
    /* Styles for more categories functionality */
    #moreCategoriesContainer {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            max-height: 200px;
            transform: translateY(0);
        }
    }
    
    #showMoreCategories {
        transition: all 0.2s ease;
    }
    
    #showMoreCategories:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    #moreCategoriesContent .btn-check:checked + .btn {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }
    
    /* Loading overlay styles */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    
    .loading-overlay .spinner-border {
        width: 3rem;
        height: 3rem;
    }
`;
document.head.appendChild(style);

// === AJAX FILTERING FUNCTIONS (GLOBAL SCOPE) ===

// AJAX-based Product Filtering with URL management
window.filterProductsByCategory = function(categoryId) {
    console.log('=== AJAX FILTER START ===');
    console.log('Filtering by category ID:', categoryId);
    
    // Show loading state
    const $productGrid = $('#productGrid');
    const $loadingOverlay = $('<div class="loading-overlay text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat produk...</p></div>');
    
    $productGrid.css('position', 'relative').prepend($loadingOverlay);
    $productGrid.find('.product-item').css('opacity', '0.5');
    
    // Build URL - use template literal approach for Laravel routes
    const baseUrl = '/peminjaman/filter/category/';
    const url = baseUrl + (categoryId || 'all');
    
    // Get search parameter if exists
    const searchParams = new URLSearchParams();
    const searchValue = $('#heroSearch').val();
    if (searchValue) {
        searchParams.append('search', searchValue);
    }
    
    const fullUrl = url + (searchParams.toString() ? '?' + searchParams.toString() : '');
    
    console.log('AJAX URL:', fullUrl);
    
    // Make AJAX request
    $.ajax({
        url: fullUrl,
        method: 'GET',
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('AJAX Success:', response);
            
            if (response.success) {
                // Update product grid
                $productGrid.html(response.html);
                
                // Update URL without refresh
                const newUrl = window.location.pathname + '?category=' + (categoryId || 'all');
                window.history.pushState({ category: categoryId }, '', newUrl);
                
                // Store current filter state for load more functionality
                window.currentCategoryFilter = categoryId;
                
                // Reset pagination state for new filter
                window.currentPage = 1;
                
                // Update page title/info
                window.updatePageInfo(response.category_name, response.count);
                
                // Update load more info if exists
                if (response.total_count && response.has_more) {
                    const loadMoreInfo = document.getElementById('loadMoreInfo');
                    if (loadMoreInfo) {
                        loadMoreInfo.textContent = 'Menampilkan 8 dari ' + response.total_count + ' produk';
                    }
                }
                
                // Re-initialize any necessary scripts
                window.initializeProductCards();
                
                console.log('Filter applied successfully. Products:', response.count);
            } else {
                throw new Error(response.message || 'Unknown error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            
            // Remove loading overlay
            $loadingOverlay.remove();
            $productGrid.find('.product-item').css('opacity', '1');
            
            // Show error message
            window.showErrorMessage('Gagal memuat produk. Silakan coba lagi.');
        },
        complete: function() {
            // Remove loading overlay
            $loadingOverlay.remove();
            $productGrid.find('.product-item').css('opacity', '1');
            
            console.log('=== AJAX FILTER END ===');
        }
    });
};

// Update page information after filtering
window.updatePageInfo = function(categoryName, productCount) {
    // NEVER change the main title/banner - keep it stable
    // Title and description should remain "Produk Unggulan" and "Koleksi terbaik dan paling diminati"
    // We only update the count information at the bottom
    
    // Only update the product count text in the products section footer
    const $productCountText = $('#productCountText');
    
    if (categoryName === 'Semua') {
        // Update product count text for all categories
        if ($productCountText.length) {
            $productCountText.text('Menampilkan ' + productCount + ' produk dari semua kategori');
        }
    } else {
        // Update product count text for specific category
        if ($productCountText.length) {
            $productCountText.text('Menampilkan ' + productCount + ' produk kategori ' + categoryName);
        }
    }
    
    console.log('Page info updated: ' + categoryName + ' (' + productCount + ' products)');
};

// Show error message
window.showErrorMessage = function(message) {
    const $alert = $('<div class="alert alert-warning alert-dismissible fade show" role="alert">' + 
        message + 
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
    
    $('#productGrid').before($alert);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        $alert.alert('close');
    }, 5000);
};

// Re-initialize product cards after AJAX load
window.initializeProductCards = function() {
    // Re-attach any event listeners that were lost
    console.log('Re-initializing product cards...');
    
    // Smooth image loading for new products
    if (typeof initSmoothImageLoading === 'function') {
        initSmoothImageLoading();
    }
    
    // Any other initialization needed for new products
};

// === TOGGLE MORE CATEGORIES FUNCTIONALITY ===

// Global function for setting up AJAX category filters
window.setupAjaxCategoryFilters = function() {
    console.log('Setting up AJAX category filters...');
    
    // Remove any existing event listeners
    $(document).off('change.ajaxFilter', 'input[name="categoryFilter"]');
    
    // Use event delegation to handle both existing and future category buttons
    $(document).on('change.ajaxFilter', 'input[name="categoryFilter"]', function() {
        const selectedCategory = $(this).attr('id');
        console.log('Category filter changed to:', selectedCategory);
        console.log('Element that triggered change:', this);
        
        // Extract category ID from the input ID
        let categoryId = 'all';
        if (selectedCategory !== 'all') {
            categoryId = selectedCategory.replace('category-', '');
        }
        
        console.log('Extracted category ID:', categoryId);
        console.log('About to call filterProductsByCategory with:', categoryId);
        
        // Call AJAX filter
        window.filterProductsByCategory(categoryId);
    });
    
    console.log('AJAX category filters setup complete with event delegation');
};

let moreCategoriesLoaded = false;

window.toggleMoreCategories = function() {
    const container = document.getElementById('moreCategoriesContainer');
    const button = document.getElementById('showMoreCategories');
    const moreText = document.getElementById('moreText');
    const moreIcon = document.getElementById('moreIcon');
    const content = document.getElementById('moreCategoriesContent');
    
    if (!container || !button) return;
    
    if (container.classList.contains('d-none')) {
        // Show more categories
        if (!moreCategoriesLoaded) {
            // Load categories via AJAX
            loadMoreCategories();
        } else {
            // Just show the container
            container.classList.remove('d-none');
            moreText.textContent = 'Sembunyikan';
            moreIcon.classList.remove('fa-chevron-down');
            moreIcon.classList.add('fa-chevron-up');
        }
    } else {
        // Hide more categories
        container.classList.add('d-none');
        moreText.innerHTML = moreText.getAttribute('data-original-text') || 'lainnya';
        moreIcon.classList.remove('fa-chevron-up');
        moreIcon.classList.add('fa-chevron-down');
    }
};

function loadMoreCategories() {
    const container = document.getElementById('moreCategoriesContainer');
    const content = document.getElementById('moreCategoriesContent');
    const moreText = document.getElementById('moreText');
    const moreIcon = document.getElementById('moreIcon');
    
    // Store original text
    if (!moreText.getAttribute('data-original-text')) {
        moreText.setAttribute('data-original-text', moreText.textContent);
    }
    
    // Show loading state
    moreText.textContent = 'Memuat...';
    moreIcon.classList.remove('fa-chevron-down');
    moreIcon.classList.add('fa-spinner', 'fa-spin');
    
    // Make AJAX request
    fetch('{{ route("peminjaman.categories.more") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            content.innerHTML = data.html;
            moreCategoriesLoaded = true;
            
            // Show container
            container.classList.remove('d-none');
            
            // Update button text
            moreText.textContent = 'Sembunyikan';
            moreIcon.classList.remove('fa-spinner', 'fa-spin');
            moreIcon.classList.add('fa-chevron-up');
            
            // No need to re-attach event listeners since we're using event delegation
            console.log('Categories loaded successfully. Event delegation will handle new buttons.');
            
        } else {
            throw new Error(data.message || 'Gagal memuat kategori');
        }
    })
    .catch(error => {
        console.error('Error loading more categories:', error);
        
        // Reset button state
        const originalText = moreText.getAttribute('data-original-text') || 'lainnya';
        moreText.textContent = originalText;
        moreIcon.classList.remove('fa-spinner', 'fa-spin');
        moreIcon.classList.add('fa-chevron-down');
        
        // Show error message
        content.innerHTML = '<div class="alert alert-warning alert-sm">Gagal memuat kategori tambahan</div>';
        container.classList.remove('d-none');
    });
}

function setupCategoryFilters() {
    console.log('Setting up category filters...');
    
    // Remove existing event listeners to prevent duplicates
    $('input[name="categoryFilter"]').off('change.categoryFilter');
    
    // Use AJAX-based filtering (now using global function)
    window.setupAjaxCategoryFilters();
    
    console.log('Category filters setup complete. Found', $('input[name="categoryFilter"]').length, 'category filters');
}

// Initialize on page load
$(document).ready(function() {
    setupCategoryFilters();
    
    // Check for category parameter in URL on page load
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    
    if (categoryParam) {
        console.log('Initial category from URL:', categoryParam);
        
        // Set the appropriate radio button
        $('input[name="categoryFilter"]').prop('checked', false);
        if (categoryParam === 'all') {
            $('#all').prop('checked', true);
            // For category=all, trigger filter to ensure consistent display
            window.filterProductsByCategory('all');
        } else {
            $('#category-' + categoryParam).prop('checked', true);
            // Apply filter for specific category
            window.filterProductsByCategory(categoryParam);
        }
    }
});

</script>