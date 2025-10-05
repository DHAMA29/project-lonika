<?php
// Test Add Item to Cart and Checkout Display

// Start session to simulate cart
session_start();

// Simulate adding items to cart (this would normally be done via the add to cart functionality)
$_SESSION['cart'] = [
    'item_1' => [
        'nama' => 'Proyektor EPSON',
        'kode' => 'PRJ-001',
        'harga' => 150000,
        'quantity' => 1,
        'id' => 1
    ],
    'item_2' => [
        'nama' => 'Laptop Gaming ASUS ROG',
        'kode' => 'LPT-002', 
        'harga' => 200000,
        'quantity' => 2,
        'id' => 2
    ]
];

echo "=== CART SIMULATION TEST ===\n";
echo "Items added to cart:\n";
foreach($_SESSION['cart'] as $key => $item) {
    echo "- {$item['nama']} ({$item['kode']}) x{$item['quantity']} @ Rp " . number_format($item['harga']) . "/hari\n";
}

echo "\n=== CHECKOUT PAGE TEST ===\n";
echo "You can now test checkout at: http://127.0.0.1:8001/peminjaman/checkout\n";
echo "Expected sidebar features:\n";
echo "✓ Rincian pesanan di kolom kanan\n";
echo "✓ Item details dengan kode barang\n";
echo "✓ Quantity dan harga per item\n";
echo "✓ Subtotal calculation\n";
echo "✓ Durasi dan total calculation\n";
echo "✓ Kode diskon input\n";
echo "✓ Action buttons\n";
echo "✓ Security info\n";

$total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));
$subtotal = 0;
foreach($_SESSION['cart'] as $item) {
    $subtotal += $item['harga'] * $item['quantity'];
}

echo "\nCart Summary:\n";
echo "Total Items: $total_items\n";
echo "Subtotal per day: Rp " . number_format($subtotal) . "\n";
?>