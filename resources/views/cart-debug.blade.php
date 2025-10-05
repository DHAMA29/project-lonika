@extends('layouts.marketplace')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Cart Simple Test</h5>
                </div>
                <div class="card-body">
                    <h6>Session Cart Contents:</h6>
                    @php
                        $cart = session()->get('cart', []);
                    @endphp
                    
                    @if(empty($cart))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Cart is empty. Use test buttons below to add items.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                    <tr>
                                        <td>{{ $id }}</td>
                                        <td>{{ $item['nama'] ?? 'N/A' }}</td>
                                        <td>Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $item['quantity'] ?? 0 }}</td>
                                        <td>Rp {{ number_format(($item['harga'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    
                    <h6 class="mt-4">Test Actions:</h6>
                    <div class="btn-group" role="group">
                        <button onclick="window.addToCart(1, 1)" class="btn btn-primary btn-sm">Add Canon EOS R6</button>
                        <button onclick="window.addToCart(2, 1)" class="btn btn-primary btn-sm">Add Item 2</button>
                        <button onclick="clearCart()" class="btn btn-danger btn-sm">Clear Cart</button>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('peminjaman.cart') }}" class="btn btn-success">
                            <i class="fas fa-shopping-cart me-2"></i>View Cart Page
                        </a>
                        <button onclick="location.reload()" class="btn btn-secondary">
                            <i class="fas fa-refresh me-2"></i>Refresh
                        </button>
                    </div>
                    
                    <h6 class="mt-4">Debug Info:</h6>
                    <div id="debug-info" class="small">
                        <div>Session ID: {{ session()->getId() }}</div>
                        <div>Cart Items Count: {{ count($cart) }}</div>
                        <div>Total Quantity: {{ array_sum(array_column($cart, 'quantity')) }}</div>
                        <div>Current Time: {{ now() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearCart() {
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
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Disable browser extension error logging temporarily
const originalConsoleError = console.error;
console.error = function(...args) {
    // Filter out browser extension errors
    const message = args.join(' ');
    if (!message.includes('message port') && 
        !message.includes('content.bundle') && 
        !message.includes('extension')) {
        originalConsoleError.apply(console, args);
    }
};
</script>
@endsection
