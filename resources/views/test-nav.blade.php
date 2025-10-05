@extends('layouts.marketplace')

@section('title', 'Test Navigation')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Navigation Test Page</h5>
                </div>
                <div class="card-body">
                    <h3>Navigation Icons Should Be Visible Above</h3>
                    <p>This page is specifically to test navigation visibility:</p>
                    <ul>
                        <li>❤️ <strong>Favorit Icon</strong> (Red Heart with label)</li>
                        <li>🛒 <strong>Keranjang Icon</strong> (Blue Cart with badge and label)</li>
                        <li>📋 <strong>Pesanan Icon</strong> (Green Clipboard with label)</li>
                        <li>👤 <strong>User Profile</strong> (Purple User icon with dropdown)</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <strong>Navigation Test:</strong> Look at the top-right corner. You should see 4 clean icons with labels underneath.
                    </div>
                    
                    <hr>
                    
                    <h4>Test Cart Functionality</h4>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="addToCart(1, 1)">
                                <i class="fas fa-plus"></i> Test Add to Cart
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success w-100" onclick="updateCartCount()">
                                <i class="fas fa-sync"></i> Update Badge
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-warning w-100" onclick="testCart()">
                                <i class="fas fa-test-tube"></i> Full Test
                            </button>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <button class="btn btn-info w-100" onclick="testPageCart()">
                                <i class="fas fa-bug"></i> Direct API Test
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-secondary w-100" onclick="clearCart()">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4>Debug Info</h4>
                    <div id="debug-output">
                        <p><strong>Current User:</strong> {{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
                        <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
                        <p><strong>Cart Items:</strong> {{ json_encode(session()->get('cart', [])) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Test function specific to this page
function testPageCart() {
    console.log('Testing from test page...');
    
    // Test direct AJAX
    fetch('/peminjaman/cart/count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('debug-output').innerHTML += 
                '<p><strong>Cart Count Response:</strong> ' + JSON.stringify(data, null, 2) + '</p>';
        })
        .catch(error => {
            document.getElementById('debug-output').innerHTML += 
                '<p><strong>Cart Count Error:</strong> ' + error.message + '</p>';
        });
}

function clearCart() {
    if (confirm('Clear all cart items?')) {
        fetch('/peminjaman/cart/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                document.getElementById('debug-output').innerHTML += 
                    '<p><strong>Cart Cleared:</strong> ' + data.message + '</p>';
            } else {
                alert('Failed to clear cart: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error clearing cart: ' + error.message);
        });
    }
}

function showCartContents() {
    fetch('/peminjaman/cart')
        .then(response => response.text())
        .then(html => {
            // Open cart page in new window
            const newWindow = window.open('', '_blank');
            newWindow.document.write(html);
        })
        .catch(error => {
            alert('Error loading cart: ' + error.message);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    testPageCart();
    
    // Monitor cart badge changes
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    document.getElementById('debug-output').innerHTML += 
                        '<p><strong>Badge Changed:</strong> ' + cartBadge.textContent + '</p>';
                }
            });
        });
        
        observer.observe(cartBadge, {
            childList: true,
            characterData: true,
            subtree: true
        });
    }
});
</script>
@endsection
