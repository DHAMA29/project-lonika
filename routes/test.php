<?php

use Illuminate\Support\Facades\Route;
use App\Models\Barang;
use App\Services\StockAvailabilityService;

// Test route for availability checking
Route::get('/test-availability/{id}', function($id) {
    $barang = Barang::findOrFail($id);
    $stockService = new StockAvailabilityService();
    
    $today = now();
    $tomorrow = now()->addDay();
    
    $availability = $stockService->checkAvailability(
        $barang->id,
        $today,
        $tomorrow
    );
    
    $result = [
        'product_id' => $barang->id,
        'product_name' => $barang->nama,
        'stock_total' => $barang->stock,
        'availability_check' => $availability,
        'request_headers' => request()->headers->all(),
        'is_ajax' => request()->ajax(),
        'wants_json' => request()->wantsJson(),
        'has_check_date' => request()->has('check_date'),
    ];
    
    if (request()->ajax() || request()->wantsJson() || request()->has('check_date')) {
        return response()->json([
            'success' => true,
            'available_stock' => $availability['available_quantity'],
            'is_available' => $availability['available'],
            'message' => $availability['message'] ?? '',
            'debug' => $result
        ]);
    }
    
    return response()->json($result);
})->middleware(['auth', 'role:user']);