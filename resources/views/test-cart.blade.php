@extends('layouts.marketplace')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Test Cart Functionality</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6><i class="fas fa-cart-plus me-2"></i>Test dengan Produk Real</h6>
                        @php
                            $testItems = App\Models\Barang::take(3)->get();
                        @endphp
                        <div class="row">
                            @foreach($testItems as $item)
                            <div class="col-md-4 mb-2">
                                <div class="card border-primary">
                                    <div class="card-body p-2">
                                        <h6 class="card-title small">{{ $item->nama }}</h6>
                                        <p class="card-text small text-muted">ID: {{ $item->id }} | Stok: {{ $item->stok }}</p>
                                        <button onclick="testAddToCart({{ $item->id }}, 1)" class="btn btn-primary btn-sm w-100">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <button onclick="clearTestCart()" class="btn btn-danger btn-sm me-2">Clear Cart</button>
                            <button onclick="testInvalidItem()" class="btn btn-warning btn-sm">Test Invalid (ID: 999)</button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Cart Information</h6>
                        <button onclick="getCartInfo()" class="btn btn-info btn-sm me-2">Get Cart Info</button>
                        <button onclick="updateCartCount()" class="btn btn-secondary btn-sm me-2">Update Badge</button>
                        <button onclick="forceShowBadge()" class="btn btn-success btn-sm">Force Show Badge</button>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-bug me-2"></i>Debug Information</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
                            <label class="form-check-label" for="autoRefresh">
                                Auto refresh debug info (every 3 seconds)
                            </label>
                        </div>
                    </div>
                    
                    <div id="cart-debug" class="mt-3">
                        <h6>Debug Output</h6>
                        <pre id="debug-output" class="bg-light p-3 rounded" style="font-size: 11px; max-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Navigation Status</h6>
                </div>
                <div class="card-body">
                    <p class="small">Periksa elemen navigasi:</p>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Logo "Lonika"</span>
                            <span class="badge bg-success">✓</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Search Bar</span>
                            <span class="badge bg-success">✓</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Icon Favorit</span>
                            <span class="badge bg-danger">❤️</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Icon Keranjang + Badge</span>
                            <span class="badge bg-primary">🛒</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Icon Pesanan</span>
                            <span class="badge bg-success">📋</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Icon Profil</span>
                            <span class="badge bg-info">👤</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <a href="{{ route('peminjaman.cart') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-shopping-cart me-1"></i>Lihat Keranjang
                        </a>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-home me-1"></i>Kembali ke Home
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips Testing</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Gunakan produk real dari database</li>
                        <li>Cek console browser untuk error</li>
                        <li>Badge akan update otomatis</li>
                        <li>Notification akan muncul saat add/clear</li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Known Issues</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Browser extension errors (not app related)</li>
                        <li>Message port errors (Chrome extension issue)</li>
                        <li>These don't affect functionality</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced test functions
function testAddToCart(barangId, quantity) {
    logDebug(`Testing addToCart(${barangId}, ${quantity})`);
    addToCart(barangId, quantity);
}

function clearTestCart() {
    logDebug('Testing clearCart()');
    fetch('/peminjaman/cart/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        logDebug('Clear cart response:', data);
        updateCartCount();
        showNotification('Cart berhasil dikosongkan!', 'success');
    })
    .catch(error => {
        logDebug('Clear cart error:', error);
        showNotification('Error clearing cart', 'error');
    });
}

function getCartInfo() {
    logDebug('Getting cart information...');
    fetch('/peminjaman/cart/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        logDebug('Cart info response:', data);
    })
    .catch(error => {
        logDebug('Cart info error:', error);
    });
}

function testInvalidItem() {
    logDebug('Testing invalid item (ID: 999)');
    addToCart(999, 1);
}

function forceShowBadge() {
    logDebug('Force showing badge for testing');
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = '5';
        badge.style.display = 'flex';
        badge.classList.remove('d-none');
        logDebug('Badge forced to show with content "5"');
    } else {
        logDebug('Badge element not found!');
    }
}

function logDebug(message, data = null) {
    const timestamp = new Date().toLocaleTimeString();
    const debugOutput = document.getElementById('debug-output');
    
    let logEntry = `[${timestamp}] ${message}`;
    if (data !== null) {
        logEntry += '\n' + JSON.stringify(data, null, 2);
    }
    
    console.log(message, data);
    
    if (debugOutput) {
        debugOutput.textContent += logEntry + '\n\n';
        debugOutput.scrollTop = debugOutput.scrollHeight;
    }
}

function monitorCartBadge() {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        const info = {
            content: badge.textContent,
            display: badge.style.display,
            classes: badge.className,
            visible: badge.offsetParent !== null
        };
        logDebug('Cart badge status:', info);
    } else {
        logDebug('Cart badge element not found!');
    }
}

// Auto refresh debug info
let autoRefreshInterval;

function startAutoRefresh() {
    stopAutoRefresh();
    autoRefreshInterval = setInterval(() => {
        if (document.getElementById('autoRefresh').checked) {
            monitorCartBadge();
        }
    }, 3000);
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    logDebug('Test page loaded');
    logDebug('CSRF Token: ' + document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
    logDebug('Current URL: ' + window.location.href);
    
    startAutoRefresh();
    
    // Initial cart badge check
    setTimeout(monitorCartBadge, 1000);
    
    // Setup auto refresh toggle
    document.getElementById('autoRefresh').addEventListener('change', function() {
        if (this.checked) {
            startAutoRefresh();
        } else {
            stopAutoRefresh();
        }
    });
});

// Cleanup on page unload
window.addEventListener('beforeunload', stopAutoRefresh);
</script>
@endsection
