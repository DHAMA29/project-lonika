<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect('/admin');
        } else {
            return redirect('/peminjaman');
        }
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect('/admin');
    } else {
        return redirect('/peminjaman');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Peminjaman routes for users only
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/cart-debug', function() {
        return view('cart-debug');
    })->name('peminjaman.cart.debug');
    Route::get('/peminjaman/test-nav', function() {
        return view('test-nav');
    })->name('peminjaman.test');
    Route::get('/peminjaman/barang/{encrypted_id}', [PeminjamanController::class, 'show'])->name('peminjaman.detail');
    Route::get('/peminjaman/show/{encrypted_id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    
    // Short route for product detail with query parameter
    Route::get('/barang', [PeminjamanController::class, 'showByQuery'])->name('barang.detail');
    Route::get('/peminjaman/cart', [PeminjamanController::class, 'cart'])->name('peminjaman.cart');
    Route::get('/peminjaman/cart/count', [PeminjamanController::class, 'cartCount'])->name('peminjaman.cart.count');
    Route::post('/peminjaman/cart/add', [PeminjamanController::class, 'addToCart'])->name('peminjaman.cart.add');
    Route::put('/peminjaman/cart/{id}/quantity', [PeminjamanController::class, 'updateCartQuantity'])->name('peminjaman.cart.update');
    Route::post('/peminjaman/cart/clear', [PeminjamanController::class, 'clearCart'])->name('peminjaman.cart.clear');
    Route::delete('/peminjaman/cart/{id}', [PeminjamanController::class, 'removeFromCart'])->name('peminjaman.cart.remove');
    Route::get('/peminjaman/checkout', [PeminjamanController::class, 'checkout'])->name('peminjaman.checkout');
    Route::get('/peminjaman/peminjam/history', [PeminjamanController::class, 'getPeminjamHistory'])->name('peminjaman.peminjam.history');
    Route::post('/peminjaman/validate-discount', [PeminjamanController::class, 'validateDiscount'])->name('validate-discount');
    Route::post('/peminjaman/checkout', [PeminjamanController::class, 'processCheckout'])->name('peminjaman.checkout.process');
    Route::get('/peminjaman/success/{encrypted_id}', [PeminjamanController::class, 'success'])->name('peminjaman.success');
    Route::get('/peminjaman/orders', [PeminjamanController::class, 'myOrders'])->name('peminjaman.orders');
    
    // Wishlist routes
    Route::get('/peminjaman/wishlist', [PeminjamanController::class, 'wishlist'])->name('peminjaman.wishlist');
    Route::get('/peminjaman/wishlist/count', [PeminjamanController::class, 'wishlistCount'])->name('peminjaman.wishlist.count');
    Route::post('/peminjaman/wishlist/add', [PeminjamanController::class, 'addToWishlist'])->name('peminjaman.wishlist.add');
    Route::delete('/peminjaman/wishlist/{barang_id}', [PeminjamanController::class, 'removeFromWishlist'])->name('peminjaman.wishlist.remove');
    Route::post('/peminjaman/wishlist/clear', [PeminjamanController::class, 'clearWishlist'])->name('peminjaman.wishlist.clear');
    
    // Load more products route
    Route::get('/peminjaman/load-more', [PeminjamanController::class, 'loadMoreProducts'])->name('peminjaman.load-more');
    
    // Get more categories route
    Route::get('/peminjaman/categories/more', [PeminjamanController::class, 'getMoreCategories'])->name('peminjaman.categories.more');
    
    // Get encrypted URL for product detail
    Route::get('/peminjaman/encrypt-url/{id}', [PeminjamanController::class, 'getEncryptedUrl'])->name('peminjaman.encrypt.url');
    
    // Batch encrypt URLs
    Route::post('/peminjaman/batch-encrypt-urls', [PeminjamanController::class, 'batchEncryptUrls'])->name('peminjaman.batch.encrypt');
    
    // Filter by category route (AJAX)
    Route::get('/peminjaman/filter/category/{id?}', [PeminjamanController::class, 'filterByCategory'])->name('peminjaman.filter.category');
    
    // Availability checking routes
    Route::post('/peminjaman/check-availability', [PeminjamanController::class, 'checkAvailability'])->name('peminjaman.check.availability');
    Route::get('/peminjaman/availability-calendar', [PeminjamanController::class, 'getAvailabilityCalendar'])->name('peminjaman.availability.calendar');
    Route::get('/peminjaman/cart-unavailable-dates', [PeminjamanController::class, 'getCartUnavailableDates'])->name('peminjaman.cart.unavailable.dates');
    
    // Testing routes (only in development)
    if (app()->environment(['local', 'development'])) {
        Route::get('/test-stock-api', function() {
            return view('test-stock-api');
        })->name('test.stock.api');
        
        Route::get('/test-encryption/{id}', function($id) {
            $encrypted = \App\Helpers\UrlCrypt::encrypt($id);
            $decrypted = \App\Helpers\UrlCrypt::decrypt($encrypted);
            
            return response()->json([
                'original' => $id,
                'encrypted' => $encrypted,
                'decrypted' => $decrypted,
                'success' => $decrypted == $id
            ]);
        })->name('test.encryption');
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Custom logout route to clear all sessions
Route::post('/custom-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/');
})->middleware('auth')->name('custom.logout');

require __DIR__.'/auth.php';

// Include test routes
if (app()->environment(['local', 'development'])) {
    require __DIR__.'/test.php';
}

// Disable forgot password routes by overriding them
Route::get('/forgot-password', function () {
    return redirect()->route('login');
})->name('password.request');

Route::post('/forgot-password', function () {
    return redirect()->route('login');
})->name('password.email');
