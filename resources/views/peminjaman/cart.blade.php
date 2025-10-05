@extends('layouts.marketplace')

@section('content')
<style>
/* Custom styles for quantity control */
.quantity-control {
    max-width: 150px;
}

.quantity-control .form-control {
    border-left: none;
    border-right: none;
    text-align: center;
    font-weight: bold;
}

.quantity-control .btn {
    border-radius: 0;
    min-width: 35px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-control .btn:first-child {
    border-radius: 0.375rem 0 0 0.375rem;
}

.quantity-control .btn:last-child {
    border-radius: 0 0.375rem 0.375rem 0;
}

.quantity-control .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Loading animation */
.fa-spinner.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Smooth transitions */
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Remove animation */
.cart-item.removing {
    opacity: 0;
    transform: translateX(-100%);
    transition: all 0.5s ease;
    margin-bottom: 0;
    padding-top: 0;
    padding-bottom: 0;
    border: none;
    overflow: hidden;
    max-height: 0;
}

.cart-item.fade-out {
    opacity: 0;
    transform: scale(0.9) rotateX(45deg);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

/* Pulse animation for items being updated */
.cart-item.updating {
    animation: pulse 1s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Shake animation for error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
    20%, 40%, 60%, 80% { transform: translateX(3px); }
}

.cart-item.shake {
    animation: shake 0.6s ease-in-out;
}

/* Success flash animation */
@keyframes flash-success {
    0% { background-color: rgba(25, 135, 84, 0); }
    50% { background-color: rgba(25, 135, 84, 0.1); }
    100% { background-color: rgba(25, 135, 84, 0); }
}

.cart-item.success-flash {
    animation: flash-success 0.8s ease-in-out;
}

/* Clear cart button animation */
.btn-clear-cart {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-clear-cart::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s;
}

.btn-clear-cart:hover::before {
    left: 100%;
}

.btn-clear-cart:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Remove button enhancement */
.btn-outline-danger {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-outline-danger:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-outline-danger::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
}

.btn-outline-danger:active::before {
    width: 300px;
    height: 300px;
}

/* Enhanced buttons */
.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Better spacing for mobile */
@media (max-width: 768px) {
    .quantity-control {
        max-width: 120px;
    }
    
    .quantity-control .form-control {
        width: 50px;
    }
    
    .quantity-control .btn {
        min-width: 30px;
        font-size: 0.8rem;
    }
}

/* Custom Notification Styles */
.custom-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 500px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border-left: 5px solid #007bff;
}

.custom-notification.show {
    transform: translateX(0);
    opacity: 1;
}

.custom-notification.notification-success {
    border-left-color: #28a745;
}

.custom-notification.notification-error {
    border-left-color: #dc3545;
}

.custom-notification.notification-warning {
    border-left-color: #ffc107;
}

.notification-content {
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notification-message {
    flex: 1;
    color: #333;
    font-weight: 500;
    font-size: 14px;
    line-height: 1.4;
}

.notification-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #666;
    cursor: pointer;
    padding: 0;
    margin-left: 15px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.notification-close:hover {
    background-color: #f8f9fa;
    color: #333;
}

/* Enhanced notification for stock errors */
.custom-notification.notification-stock-error {
    border-left-color: #ff6b35;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
}

/* Pulsing effect for urgent notifications */
@keyframes notification-pulse {
    0% { transform: translateX(0) scale(1); }
    50% { transform: translateX(0) scale(1.02); }
    100% { transform: translateX(0) scale(1); }
}

.custom-notification.urgent {
    animation: notification-pulse 2s ease-in-out infinite;
}

/* Media Queries for Notifications */
@media (max-width: 768px) {
    .custom-notification {
        top: 10px;
        right: 10px;
        left: 10px;
        min-width: auto;
        max-width: none;
    }
}
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja
            </h2>
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>
    </div>
    
    @if(empty($cartItems))
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart display-1 text-muted mb-4"></i>
                <h3 class="mb-3">Keranjang Kosong</h3>
                <p class="text-muted mb-4">Belum ada barang yang ditambahkan ke keranjang</p>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Item dalam Keranjang ({{ count($cartItems) }})
                        </h5>
                        <button type="button" class="btn btn-outline-light btn-sm btn-clear-cart" onclick="clearAllCart()">
                            <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @foreach($cartItems as $id => $item)
                    <div class="d-flex align-items-center p-3 border-bottom cart-item" data-item-id="{{ $id }}">
                        <div class="flex-shrink-0 me-3">
                            @if(isset($item['image']) && $item['image'] && $item['image'] !== 'placeholder.jpg' && file_exists(public_path('storage/' . $item['image'])))
                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['nama'] }}" 
                                     class="rounded" 
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     loading="lazy">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item['nama'] }}</h6>
                            <p class="text-muted small mb-2">Harga: Rp {{ number_format($item['harga'], 0, ',', '.') }} / hari</p>
                            
                            <!-- Quantity Control -->
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">Jumlah:</span>
                                <div class="quantity-control d-flex align-items-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity('{{ $id }}', -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           class="form-control form-control-sm text-center mx-2" 
                                           id="quantity-{{ $id }}" 
                                           value="{{ $item['quantity'] }}" 
                                           min="1" 
                                           max="999"
                                           style="width: 70px;"
                                           onchange="updateQuantityDirect('{{ $id }}', this.value)"
                                           data-barang-id="{{ $id }}">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity('{{ $id }}', 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Loading indicator -->
                            <div id="loading-{{ $id }}" class="d-none">
                                <small class="text-muted">
                                    <i class="fas fa-spinner fa-spin"></i> Memperbarui...
                                </small>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <div class="h6 text-primary mb-2" id="item-total-{{ $id }}">
                                Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}
                            </div>
                            <small class="text-muted d-block mb-2">per hari</small>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeItemWithAnimation('{{ $id }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                </a>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px; z-index: 1020;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Ringkasan Belanja
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $subtotal = 0;
                        foreach($cartItems as $item) {
                            $subtotal += $item['harga'] * $item['quantity'];
                        }
                    @endphp
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal (per hari):</span>
                        <strong class="text-primary" id="grand-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Total final akan dihitung berdasarkan durasi peminjaman yang Anda pilih saat checkout.
                    </div>
                    
                    <div class="d-grid mb-3">
                        <a href="{{ route('peminjaman.checkout') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Checkout
                        </a>
                    </div>
                    
                    <div class="small text-muted">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Ketentuan:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Pembayaran dilakukan di muka</li>
                            <li>Barang harus dikembalikan tepat waktu, Denda keterlambatan Rp 10.000/hari</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Clear Cart Confirmation Modal -->
<div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="clearCartModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Keranjang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                    <h5>Yakin ingin mengosongkan keranjang?</h5>
                    <p class="text-muted">Semua item dalam keranjang akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <div class="alert alert-warning">
                        <strong>{{ count($cartItems ?? []) }} item</strong> akan dihapus dari keranjang Anda.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmClearCart()" data-bs-dismiss="modal">
                    <i class="fas fa-trash me-2"></i>Ya, Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Cart page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page loaded');
    
    // Update cart count on page load
    updateCartCount();
    
    // Auto-refresh cart count every 10 seconds
    setInterval(function() {
        updateCartCount();
    }, 10000);
});

// Remove item with animation (NO POPUP CONFIRMATION)
function removeItemWithAnimation(itemId) {
    const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
    
    if (cartItem) {
        // Add removing class for animation immediately (no confirmation)
        cartItem.classList.add('removing');
        
        // Wait for animation to complete before actually removing
        setTimeout(() => {
            // Make actual delete request
            fetch(`/peminjaman/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            })
            .then(data => {
                if (data && data.success) {
                    // Remove the item from DOM
                    cartItem.remove();
                    
                    // Update cart count
                    updateCartCount();
                    
                    // Recalculate totals
                    recalculateCartTotals();
                    
                    // Show success message
                    showCustomNotification('✅ Item berhasil dihapus dari keranjang', 'success');
                    
                    // Check if cart is empty and reload if needed
                    const remainingItems = document.querySelectorAll('.cart-item:not(.removing)');
                    if (remainingItems.length === 0) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Add success flash to remaining items
                        remainingItems.forEach(item => {
                            item.classList.add('success-flash');
                            setTimeout(() => {
                                item.classList.remove('success-flash');
                            }, 800);
                        });
                    }
                } else {
                    throw new Error(data?.message || 'Failed to remove item');
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                
                // Remove animation class if failed
                cartItem.classList.remove('removing');
                cartItem.classList.add('shake');
                
                setTimeout(() => {
                    cartItem.classList.remove('shake');
                }, 600);
                
                // Handle specific error types
                if (error.message.includes('message port')) {
                    // Browser extension error - ignore
                    console.log('Browser extension error ignored:', error.message);
                    return;
                }
                
                showCustomNotification('⚠️ Terjadi kesalahan saat menghapus item', 'error');
            });
        }, 500); // Wait for animation
    }
}

// Custom notification system (no browser notifications)
function showCustomNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `custom-notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show with animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 4000);
}

// Clear entire cart with modal confirmation
function clearAllCart() {
    // Show modal instead of simple confirm
    const modal = new bootstrap.Modal(document.getElementById('clearCartModal'));
    modal.show();
}

// Confirm clear cart (called from modal)
function confirmClearCart() {
    // Add loading state to clear button
    const clearButton = document.querySelector('.btn-clear-cart');
    const originalText = clearButton.innerHTML;
    clearButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';
    clearButton.disabled = true;
    
    // Animate all cart items with staggered effect
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('fade-out');
        }, index * 100); // Stagger the animation
    });
    
    // Make request to clear cart
    fetch('/peminjaman/cart/clear', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCustomNotification('🎉 Keranjang berhasil dikosongkan', 'success');
            
            // Wait for animation to complete then reload
            setTimeout(() => {
                window.location.reload();
            }, 1200);
        } else {
            // Restore items if failed
            cartItems.forEach(item => {
                item.classList.remove('fade-out');
                item.classList.add('shake');
                setTimeout(() => {
                    item.classList.remove('shake');
                }, 600);
            });
            
            // Restore button
            clearButton.innerHTML = originalText;
            clearButton.disabled = false;
            
            showCustomNotification('❌ ' + (data.message || 'Gagal mengosongkan keranjang'), 'error');
        }
    })
    .catch(error => {
        console.error('Error clearing cart:', error);
        
        // Restore items if failed
        cartItems.forEach(item => {
            item.classList.remove('fade-out');
            item.classList.add('shake');
            setTimeout(() => {
                item.classList.remove('shake');
            }, 600);
        });
        
        // Restore button
        clearButton.innerHTML = originalText;
        clearButton.disabled = false;
        
        showCustomNotification('⚠️ Terjadi kesalahan saat mengosongkan keranjang', 'error');
    });
}

// Recalculate cart totals after item removal
function recalculateCartTotals() {
    let grandTotal = 0;
    const cartItems = document.querySelectorAll('.cart-item:not(.removing)');
    
    cartItems.forEach(item => {
        const itemId = item.dataset.itemId;
        const quantityInput = document.getElementById('quantity-' + itemId);
        const itemTotalElement = document.getElementById('item-total-' + itemId);
        
        if (quantityInput && itemTotalElement) {
            const quantity = parseInt(quantityInput.value) || 0;
            // Extract price from the item total text (this is a simple approach)
            const itemTotalText = itemTotalElement.textContent;
            const itemTotal = parseInt(itemTotalText.replace(/[^\d]/g, '')) || 0;
            grandTotal += itemTotal;
        }
    });
    
    // Update grand total display
    const grandTotalElement = document.getElementById('grand-total');
    if (grandTotalElement) {
        grandTotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }
    
    // Update cart count in header
    const headerCountElement = document.querySelector('.card-header h5');
    if (headerCountElement) {
        const remainingCount = cartItems.length;
        headerCountElement.innerHTML = `<i class="fas fa-list me-2"></i>Item dalam Keranjang (${remainingCount})`;
    }
}

// Update quantity function
function updateQuantity(itemId, change) {
    const quantityInput = document.getElementById('quantity-' + itemId);
    const currentQuantity = parseInt(quantityInput.value) || 1;
    const newQuantity = Math.max(1, currentQuantity + change);
    
    if (newQuantity !== currentQuantity) {
        quantityInput.value = newQuantity;
        updateQuantityDirect(itemId, newQuantity);
    }
}

// Update quantity directly
function updateQuantityDirect(itemId, quantity) {
    const parsedQuantity = parseInt(quantity);
    
    if (isNaN(parsedQuantity) || parsedQuantity < 1) {
        document.getElementById('quantity-' + itemId).value = 1;
        return;
    }
    
    // Show loading
    const loadingElement = document.getElementById('loading-' + itemId);
    if (loadingElement) {
        loadingElement.classList.remove('d-none');
    }
    
    // Disable buttons temporarily
    const buttons = document.querySelectorAll(`[onclick*="${itemId}"]`);
    buttons.forEach(btn => btn.disabled = true);
    
    fetch(`/peminjaman/cart/${itemId}/quantity`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: parsedQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item total
            const itemTotalElement = document.getElementById('item-total-' + itemId);
            if (itemTotalElement) {
                itemTotalElement.textContent = 'Rp ' + data.item_total;
            }
            
            // Update grand total
            const grandTotalElement = document.getElementById('grand-total');
            if (grandTotalElement) {
                grandTotalElement.textContent = 'Rp ' + data.grand_total;
            }
            
            // Update cart count in navigation
            updateCartCount();
            
            // Show success message
            showCustomNotification('✅ Jumlah berhasil diperbarui', 'success');
            
            // Update quantity input in case it was corrected
            document.getElementById('quantity-' + itemId).value = data.quantity;
        } else {
            // Handle stock shortage with custom notification
            if (data.message && data.message.includes('stok')) {
                showStockErrorNotification(data.message, itemId);
            } else {
                showCustomNotification('⚠️ ' + (data.message || 'Gagal memperbarui jumlah'), 'error');
            }
            
            // Reset to original quantity if failed
            if (data.original_quantity) {
                document.getElementById('quantity-' + itemId).value = data.original_quantity;
            } else {
                location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        showCustomNotification('⚠️ Terjadi kesalahan saat memperbarui jumlah', 'error');
        location.reload();
    })
    .finally(() => {
        // Hide loading
        if (loadingElement) {
            loadingElement.classList.add('d-none');
        }
        
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
    });
}

// Show stock error notification with enhanced UI
function showStockErrorNotification(message, itemId) {
    // Create enhanced notification for stock errors
    const notification = document.createElement('div');
    notification.className = 'custom-notification notification-warning';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 350px;
        max-width: 500px;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
        border-left: 5px solid #ff6b35;
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    `;
    
    notification.innerHTML = `
        <div style="padding: 20px; display: flex; align-items: flex-start; gap: 15px;">
            <div style="flex-shrink: 0;">
                <div style="width: 40px; height: 40px; background: #ff6b35; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-triangle" style="color: white; font-size: 18px;"></i>
                </div>
            </div>
            <div style="flex: 1;">
                <h6 style="margin: 0 0 8px 0; color: #333; font-weight: 600;">Stok Tidak Mencukupi</h6>
                <p style="margin: 0 0 12px 0; color: #666; font-size: 14px; line-height: 1.4;">${message}</p>
                <div style="display: flex; gap: 10px;">
                    <button onclick="this.closest('.custom-notification').remove()" 
                            style="background: #ff6b35; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s;">
                        Mengerti
                    </button>
                </div>
            </div>
            <button onclick="this.closest('.custom-notification').remove()" 
                    style="background: none; border: none; font-size: 18px; color: #999; cursor: pointer; padding: 0; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                &times;
            </button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Highlight the problematic item
    const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
    if (cartItem) {
        cartItem.classList.add('shake');
        cartItem.style.background = 'linear-gradient(135deg, #ffebee 0%, #fce4ec 100%)';
        
        setTimeout(() => {
            cartItem.classList.remove('shake');
            cartItem.style.background = '';
        }, 2000);
    }
    
    // Show with animation
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 100);
    
    // Auto remove after 6 seconds (longer for important stock messages)
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 400);
        }
    }, 6000);
}

// Add keyboard support for quantity inputs
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('[id^="quantity-"]');
    
    quantityInputs.forEach(input => {
        input.addEventListener('keydown', function(e) {
            const itemId = this.dataset.barangId;
            
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                updateQuantity(itemId, 1);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                updateQuantity(itemId, -1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                this.blur(); // Trigger change event
            }
        });
        
        // Add debounced input handler
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const itemId = this.dataset.barangId;
            const value = this.value;
            
            timeout = setTimeout(() => {
                updateQuantityDirect(itemId, value);
            }, 1000); // Wait 1 second after user stops typing
        });
    });
});

// Make functions globally accessible
window.updateQuantity = updateQuantity;
window.updateQuantityDirect = updateQuantityDirect;
window.removeItemWithAnimation = removeItemWithAnimation;
window.clearAllCart = clearAllCart;
window.confirmClearCart = confirmClearCart;
window.recalculateCartTotals = recalculateCartTotals;
window.showCustomNotification = showCustomNotification;
window.showStockErrorNotification = showStockErrorNotification;
</script>
@endsection
